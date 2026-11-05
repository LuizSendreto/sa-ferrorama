
<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include '../../infra/conexao.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$erro = "";

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Guardar dados digitados se houver erro
$nome = "";
$email = "";
$telefone = "";
$cep = "";

// NOVO: consultar o endereço na API ViaCEP
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
        CURLOPT_SSL_VERIFYPEER => true
    ]);

    $resposta = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($resposta === false || $status !== 200) {
        throw new RuntimeException(
            "Não foi possível consultar o CEP."
        );
    }

    $dados = json_decode($resposta, true);

    if (!is_array($dados)) {
        throw new RuntimeException(
            "Resposta inválida da API."
        );
    }

    if (!empty($dados['erro'])) {
        throw new RuntimeException(
            "CEP não encontrado."
        );
    }

    if (empty($dados['localidade']) || empty($dados['uf'])) {
        throw new RuntimeException(
            "Endereço incompleto."
        );
    }

    return [
        "cep" => $cep,
        "logradouro" => $dados['logradouro'] ?? '',
        "bairro" => $dados['bairro'] ?? '',
        "cidade" => $dados['localidade'],
        "uf" => $dados['uf']
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar token CSRF
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        exit("Solicitação inválida.");
    }

    $nome = trim($_POST['nomeUsuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmarSenha'] ?? '';

    // NOVO: receber CEP do formulário
    $cep = trim($_POST['cep'] ?? '');

    if ($nome === "" || $email === "" || $telefone === "" || $senha === "") {
        $erro = "Preencha todos os campos.";

    } elseif (mb_strlen($nome) > 100) {
        $erro = "O nome pode ter no máximo 100 caracteres.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $erro = "Informe um e-mail válido.";

    } elseif (strlen($telefone) > 20) {
        $erro = "O telefone pode ter no máximo 20 caracteres.";

    } elseif (strlen($senha) < 8) {
        $erro = "A senha deve ter pelo menos 8 caracteres.";

    } elseif ($senha !== $confirmarSenha) {
        $erro = "As senhas não são iguais.";

    // NOVO: validar CEP
    } elseif (!preg_match('/^\d{5}-?\d{3}$/', $cep)) {
        $erro = "CEP inválido! Digite 8 números.";

    } else {

        // Criptografar a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Definir o tipo e o nível de acesso
        $tipo = "funcionario";
        $nivel_acesso = "comum";

        $transacaoIniciada = false;

        try {

            // NOVO: consultar o CEP antes de cadastrar
            $cepNumerico = str_replace("-", "", $cep);

            $endereco = consultarViaCep($cepNumerico);

            // NOVO: iniciar transação
            $conn->begin_transaction();
            $transacaoIniciada = true;

            // CADASTRO ORIGINAL NA TABELA USUARIOS
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

            // NOVO: recuperar ID do funcionário
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

            // Retornar para a listagem
            header("Location: ../usuarios/visu_usuarios.php");
            exit;

        } catch (Throwable $e) {

            // NOVO: desfazer cadastro se houver erro
            if ($transacaoIniciada) {
                $conn->rollback();
            }

            if (
                $e instanceof mysqli_sql_exception &&
                $e->getCode() == 1062
            ) {
                $erro = "Já existe um usuário com esse e-mail.";

            } elseif ($e instanceof RuntimeException) {
                $erro = $e->getMessage();

            } else {
                error_log($e->getMessage());
                $erro = "Erro ao cadastrar funcionário.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário ● Strain</title>
    <link rel="stylesheet" href="../../assets/style/style.css?v=cadastrofuncionarios2">
</head>
<body class="body_dashboard">

    <header>
        <nav class="navbar">
            <div class="marca-strain">
                <i data-lucide="train-front"></i>
                <span>STRAIN</span>
            </div>
            <a href="../usuarios/visu_usuarios.php" class="btn">Voltar</a>
        </nav>
    </header>

    <main class="area_formulario">
        <section class="painel_formulario">
            <h1 class="titulo_formulario">Cadastrar Funcionário</h1>

            <?php if ($erro !== "") { ?>
                <div class="mensagem_erro_formulario" role="alert">
                    <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php } ?>

            <form id="form_cadastro" class="formulario_strain" action="cadastro_funcionarios.php" method="POST">
                <input type="hidden" name="csrf_token"
                       value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <div class="campo_formulario">
                    <label for="nomeUsuario">Nome do funcionário</label>
                    <input type="text" id="nomeUsuario" name="nomeUsuario" maxlength="100"
                           placeholder="Digite o nome completo"
                           value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="campo_formulario">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" maxlength="100"
                           placeholder="Digite o e-mail"
                           value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="campo_formulario">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" maxlength="20"
                           placeholder="Digite o telefone"
                           value="<?= htmlspecialchars($telefone, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="campo_formulario">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" minlength="8"
                           autocomplete="new-password" placeholder="Mínimo de 8 caracteres" required>
                </div>

                <div class="campo_formulario">
                    <label for="confirmarSenha">Confirmar senha</label>
                    <input type="password" id="confirmarSenha" name="confirmarSenha" minlength="8"
                           autocomplete="new-password" placeholder="Digite novamente a senha" required>
                </div>

                <h2 class="titulo_endereco_formulario">Endereço do funcionário</h2>
                <p class="descricao_endereco_formulario">Digite o CEP para preencher o endereço automaticamente.</p>

                <div class="campo_formulario">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" maxlength="9" inputmode="numeric"
                           autocomplete="postal-code" placeholder="00000-000"
                           value="<?= htmlspecialchars($cep, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="campo_formulario">
                    <label for="logradouro">Logradouro</label>
                    <input type="text" id="logradouro" placeholder="Preenchido pelo CEP" readonly>
                </div>

                <div class="campo_formulario">
                    <label for="bairro">Bairro</label>
                    <input type="text" id="bairro" placeholder="Preenchido pelo CEP" readonly>
                </div>

                <div class="campo_formulario">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" placeholder="Preenchido pelo CEP" readonly>
                </div>

                <div class="campo_formulario">
                    <label for="uf">Estado (UF)</label>
                    <input type="text" id="uf" placeholder="Preenchido pelo CEP" readonly>
                </div>

                <p id="mensagem_cep_cadastro" role="status" aria-live="polite"></p>

                <button type="submit" class="botao_formulario">Cadastrar Funcionário</button>
            </form>
        </section>
    </main>

    <!-- O cep.js já consulta e preenche os campos no cadastro. -->
     <script>
    document.addEventListener("DOMContentLoaded", function () {

        const campoCep = document.getElementById("cep");
        const logradouro = document.getElementById("logradouro");
        const bairro = document.getElementById("bairro");
        const cidade = document.getElementById("cidade");
        const uf = document.getElementById("uf");
        const mensagem = document.getElementById("mensagem_cep_cadastro");

        let temporizador;
        let controlador;
        let consultaAtual = 0;

        function limparEndereco() {
            logradouro.value = "";
            bairro.value = "";
            cidade.value = "";
            uf.value = "";
        }

        function mostrarMensagem(texto, tipo = "") {
            mensagem.textContent = texto;
            mensagem.dataset.tipo = tipo;
        }

        async function consultarCep(cep) {

            const numeroConsulta = ++consultaAtual;

            controlador = new AbortController();

            const controle = controlador;

            const limite = setTimeout(() => {
                controle.abort();
            }, 10000);

            mostrarMensagem("Consultando CEP...");

            try {

                const resposta = await fetch(
                    `https://viacep.com.br/ws/${cep}/json/`,
                    { signal: controle.signal }
                );

                if (!resposta.ok) {
                    throw new Error("Erro na consulta.");
                }

                const dados = await resposta.json();

                // Ignorar respostas antigas
                if (
                    numeroConsulta !== consultaAtual ||
                    campoCep.value.replace(/\D/g, "") !== cep
                ) {
                    return;
                }

                if (dados.erro) {
                    limparEndereco();
                    mostrarMensagem("CEP não encontrado.", "erro");
                    return;
                }

                campoCep.value = dados.cep || campoCep.value;
                logradouro.value = dados.logradouro || "";
                bairro.value = dados.bairro || "";
                cidade.value = dados.localidade || "";
                uf.value = dados.uf || "";

                mostrarMensagem(
                    "Endereço encontrado com sucesso!",
                    "sucesso"
                );

            } catch (erro) {

                if (numeroConsulta !== consultaAtual) {
                    return;
                }

                limparEndereco();

                if (erro.name === "AbortError") {
                    mostrarMensagem(
                        "A consulta demorou muito. Tente novamente.",
                        "erro"
                    );
                } else {
                    mostrarMensagem(
                        "Falha ao consultar o CEP.",
                        "erro"
                    );
                }

            } finally {
                clearTimeout(limite);
            }
        }

        campoCep.addEventListener("input", function () {

            clearTimeout(temporizador);
            consultaAtual++;

            if (controlador) {
                controlador.abort();
            }

            let cep = campoCep.value.replace(/\D/g, "").slice(0, 8);

            if (cep.length > 5) {
                campoCep.value =
                    cep.slice(0, 5) + "-" + cep.slice(5);
            } else {
                campoCep.value = cep;
            }

            limparEndereco();
            mostrarMensagem("");

            // Consultar automaticamente ao digitar 8 números
            if (cep.length === 8) {
                temporizador = setTimeout(function () {
                    consultarCep(cep);
                }, 400);

            } else if (cep.length > 0) {
                mostrarMensagem("Digite os 8 números do CEP.");
            }
        });

        // Reconsultar após erro no cadastro
        const cepInicial = campoCep.value.replace(/\D/g, "");

        if (cepInicial.length === 8) {
            consultarCep(cepInicial);
        }

    });
    </script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
