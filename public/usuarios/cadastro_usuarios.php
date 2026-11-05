
<?php

session_start();

include '../../infra/conexao.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$erro = "";

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Guardar os campos digitados em caso de erro
$nome = "";
$email = "";
$telefone = "";
$cep = "";

// NOVO: consultar e validar o CEP no servidor
function consultarViaCep($cep) {

    if (!function_exists('curl_init')) {
        throw new RuntimeException(
            "A extensão cURL não está habilitada no PHP."
        );
    }

    $url = "https://viacep.com.br/ws/" . $cep . "/json/";

    $curl = curl_init($url);

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);

    $resposta = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    // Falha de comunicação
    if ($resposta === false || $status !== 200) {
        throw new RuntimeException(
            "Não foi possível consultar o CEP. Tente novamente."
        );
    }

    $dados = json_decode($resposta, true);

    if (!is_array($dados)) {
        throw new RuntimeException(
            "Resposta inválida da API ViaCEP."
        );
    }

    // CEP inexistente
    if (!empty($dados['erro'])) {
        throw new RuntimeException(
            "CEP não encontrado."
        );
    }

    if (empty($dados['localidade']) || empty($dados['uf'])) {
        throw new RuntimeException(
            "O endereço retornado está incompleto."
        );
    }

    return [
        'cep' => $cep,
        'logradouro' => $dados['logradouro'] ?? '',
        'bairro' => $dados['bairro'] ?? '',
        'cidade' => $dados['localidade'],
        'uf' => $dados['uf']
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificar o token CSRF
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !hash_equals(
            $_SESSION['csrf_token'],
            $_POST['csrf_token']
        )
    ) {
        http_response_code(403);
        exit("Solicitação inválida.");
    }

    $nome = trim($_POST['nomeUsuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmarSenha'] ?? '';

    // NOVO: receber o CEP do formulário
    $cep = trim($_POST['cep'] ?? '');

    // Validar os campos
    if ($nome === "" || $email === "" ||
        $telefone === "" || $senha === "") {

        $erro = "Preencha todos os campos.";

    } elseif (strlen($nome) > 100) {

        $erro = "O nome pode ter no máximo 100 caracteres.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) ||
              strlen($email) > 100) {

        $erro = "Informe um e-mail válido.";

    } elseif (strlen($telefone) > 20) {

        $erro = "O telefone pode ter no máximo 20 caracteres.";

    } elseif (strlen($senha) < 8) {

        $erro = "A senha deve ter pelo menos 8 caracteres.";

    } elseif ($senha !== $confirmarSenha) {

        $erro = "As senhas não são iguais.";

    // NOVO: validar o formato do CEP
    } elseif (!preg_match('/^\d{5}-?\d{3}$/', $cep)) {

        $erro = "CEP inválido! Digite 8 números.";

    } else {

        // Proteger a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Todo cadastro público será de cliente comum
        $tipo = "cliente";
        $nivel_acesso = "comum";

        $transacaoIniciada = false;

        try {

            // NOVO: remover o hífen do CEP
            $cepNumerico = str_replace("-", "", $cep);

            // NOVO: consultar endereço real no ViaCEP
            $endereco = consultarViaCep($cepNumerico);

            // NOVO: iniciar transação no banco
            $conn->begin_transaction();
            $transacaoIniciada = true;

            // CADASTRO ORIGINAL DO USUÁRIO
            $sql = "INSERT INTO usuarios
                    (nome, email, telefone, senha, tipo, nivel_acesso)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssssss",
                $nome,
                $email,
                $telefone,
                $senha_hash,
                $tipo,
                $nivel_acesso
            );

            $stmt->execute();

            // NOVO: recuperar o ID do usuário cadastrado
            $usuario_id = $conn->insert_id;

            $stmt->close();

            // NOVO: salvar endereço com Foreign Key
            $sqlEndereco = "INSERT INTO enderecos_usuarios
                (usuario_id, cep, logradouro, bairro, cidade, uf)
                VALUES (?, ?, ?, ?, ?, ?)";

            $stmtEndereco = $conn->prepare($sqlEndereco);

            $logradouro = $endereco['logradouro'];
            $bairro = $endereco['bairro'];
            $cidade = $endereco['cidade'];
            $uf = $endereco['uf'];

            $stmtEndereco->bind_param(
                "isssss",
                $usuario_id,
                $cepNumerico,
                $logradouro,
                $bairro,
                $cidade,
                $uf
            );

            $stmtEndereco->execute();
            $stmtEndereco->close();

            // NOVO: confirmar os dois cadastros
            $conn->commit();
            $transacaoIniciada = false;

            header("Location: login.php");
            exit;

        } catch (Throwable $e) {

            // Desfazer o cadastro se houver erro
            if ($transacaoIniciada) {
                $conn->rollback();
            }

            if (
                $e instanceof mysqli_sql_exception &&
                $e->getCode() == 1062
            ) {
                $erro = "Já existe uma conta com esse e-mail.";

            } elseif ($e instanceof RuntimeException) {

                $erro = $e->getMessage();

            } else {

                error_log($e->getMessage());
                $erro = "Erro ao realizar o cadastro.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html class="login" lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Usuário ● Strain</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- CSS ORIGINAL -->
    <link rel="stylesheet"
          href="../../assets/style/style.css">

    <!-- NOVO: CSS DO CEP -->
    <link rel="stylesheet"
          href="../../assets/style/cep.css">

    <!-- NOVO: JAVASCRIPT QUE JÁ CRIAMOS -->
    <script src="../../Scripts/cep.js" defer></script>

</head>

<body class="body_telalogin body_cadastro_cep">

    <header>
        <nav></nav>
    </header>

    <div class="Strain">
        <img class="Strain"
             src="../../assets/img/Tela de login atualizada.png"
             alt="Strain">
    </div>

    <div class="flex">

        <main class="blue">

            <div class="container_login">

                <h2 id="titulo_cadastro">Cadastrar</h2>

                <?php if ($erro != "") { ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars(
                            $erro,
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>
                    </div>
                <?php } ?>

                <form id="form_login" method="POST" action="">

                    <input type="hidden"
                           name="csrf_token"
                           value="<?php echo htmlspecialchars(
                               $_SESSION['csrf_token'],
                               ENT_QUOTES,
                               'UTF-8'
                           ); ?>">

                    <!-- EMAIL ORIGINAL -->
                    <div class="conjunto">
                        <label for="email" class="form-label"></label>

                        <input type="email"
                               id="email"
                               name="email"
                               placeholder="Digite seu email"
                               value="<?php echo htmlspecialchars(
                                   $email,
                                   ENT_QUOTES,
                                   'UTF-8'
                               ); ?>"
                               maxlength="100"
                               required>
                    </div>

                    <!-- NOME ORIGINAL -->
                    <div class="conjunto">
                        <label for="nomeUsuario" class="form-label"></label>

                        <input type="text"
                               id="nomeUsuario"
                               name="nomeUsuario"
                               placeholder="Digite seu nome de usuário"
                               value="<?php echo htmlspecialchars(
                                   $nome,
                                   ENT_QUOTES,
                                   'UTF-8'
                               ); ?>"
                               maxlength="100"
                               required>
                    </div>

                    <!-- SENHA ORIGINAL -->
                    <div class="conjunto">
                        <label for="senha" class="form-label"></label>

                        <input type="password"
                               id="senha"
                               name="senha"
                               placeholder="Digite sua senha"
                               minlength="8"
                               required>
                    </div>

                    <!-- CONFIRMAR SENHA ORIGINAL -->
                    <div class="conjunto">
                        <label for="confirmarSenha" class="form-label"></label>

                        <input type="password"
                               id="confirmarSenha"
                               name="confirmarSenha"
                               placeholder="Confirme sua senha"
                               minlength="8"
                               required>
                    </div>

                    <!-- TELEFONE ORIGINAL -->
                    <div class="conjunto">
                        <label for="telefone" class="form-label"></label>

                        <input type="text"
                               id="telefone"
                               name="telefone"
                               placeholder="Digite seu telefone"
                               value="<?php echo htmlspecialchars(
                                   $telefone,
                                   ENT_QUOTES,
                                   'UTF-8'
                               ); ?>"
                               maxlength="20"
                               required>
                    </div>

                    <!-- NOVO: CADASTRO DE ENDEREÇO -->
                    <fieldset class="cadastro-endereco">

                        <legend>Meu endereço</legend>

                        <p>
                            Digite seu CEP para preencher
                            seu endereço automaticamente.
                        </p>

                        <div class="conjunto">

                            <label for="cep">CEP:</label>

                            <div class="linha_cep">

                                <input type="text"
                                       id="cep"
                                       name="cep"
                                       placeholder="00000-000"
                                       maxlength="9"
                                       inputmode="numeric"
                                       value="<?php echo htmlspecialchars(
                                           $cep,
                                           ENT_QUOTES,
                                           'UTF-8'
                                       ); ?>"
                                       required>

                                <button type="button"
                                        id="consultar_cep">
                                    Consultar
                                </button>

                            </div>
                        </div>

                        <div class="conjunto">
                            <label for="logradouro">
                                Logradouro:
                            </label>

                            <input type="text"
                                   id="logradouro"
                                   placeholder="Rua ou avenida"
                                   readonly>
                        </div>

                        <div class="conjunto">
                            <label for="bairro">
                                Bairro:
                            </label>

                            <input type="text"
                                   id="bairro"
                                   placeholder="Bairro"
                                   readonly>
                        </div>

                        <div class="conjunto">
                            <label for="cidade">
                                Cidade:
                            </label>

                            <input type="text"
                                   id="cidade"
                                   placeholder="Cidade"
                                   readonly>
                        </div>

                        <div class="conjunto">
                            <label for="uf">
                                Estado (UF):
                            </label>

                            <input type="text"
                                   id="uf"
                                   placeholder="UF"
                                   readonly>
                        </div>

                        <p id="mensagem_cep_cadastro"
                           role="status"
                           aria-live="polite"></p>

                    </fieldset>
                    <!-- FIM CADASTRO DE ENDEREÇO -->

                    <!-- CHECKBOX ORIGINAL -->
                    <div class="col-12">
                        <div class="form-check">

                            <input class="form-check-input"
                                   type="checkbox"
                                   id="gridCheck">

                            <label class="form-check-label"
                                   for="gridCheck">
                                Permito o compartilhamento
                                da minha localização
                            </label>

                        </div>
                    </div>

                    <!-- BOTÃO ORIGINAL -->
                    <button id="botao_login"
                            type="submit"
                            class="btn btn-outline-danger">
                        Cadastrar-se
                    </button>

                </form>

            </div>
        </main>
    </div>

    <footer></footer>

</body>
</html>
