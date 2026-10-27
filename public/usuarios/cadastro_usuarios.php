
<?php

session_start();

include '../../infra/conexao.php';

$erro = "";

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Guardar os campos digitados em caso de erro
$nome = "";
$email = "";
$telefone = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificar o token CSRF
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

    // Validar os campos
    if ($nome === "" || $email === "" || $telefone === "" || $senha === "") {
        $erro = "Preencha todos os campos.";

    } elseif (strlen($nome) > 100) {
        $erro = "O nome pode ter no máximo 100 caracteres.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $erro = "Informe um e-mail válido.";

    } elseif (strlen($telefone) > 20) {
        $erro = "O telefone pode ter no máximo 20 caracteres.";

    } elseif (strlen($senha) < 8) {
        $erro = "A senha deve ter pelo menos 8 caracteres.";

    } elseif ($senha !== $confirmarSenha) {
        $erro = "As senhas não são iguais.";

    } else {

        // Proteger a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Todo cadastro público será de cliente comum
        $tipo = "cliente";
        $nivel_acesso = "comum";

        try {

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
            $stmt->close();

            header("Location: login.php");
            exit;

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {
                $erro = "Já existe uma conta com esse e-mail.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>

<body class="body_telalogin">
    <header>
        <nav></nav>
    </header>

    <div class="Strain">
        <img class="Strain" src="../../assets/img/Tela de login atualizada.png" alt="Strain">
    </div>

    <div class="flex">
        <main class="blue">
            <div class="container_login">
                <h2 id="titulo_cadastro">Cadastrar</h2>

                <?php if ($erro != "") { ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php } ?>

                <form id="form_login" method="POST" action="">

                    <input type="hidden" name="csrf_token"
                           value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="conjunto">
                        <label for="email" class="form-label"></label>
                        <input type="email" id="email" name="email"
                               placeholder="Digite seu email"
                               value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                               maxlength="100" required>
                    </div>

                    <div class="conjunto">
                        <label for="nomeUsuario" class="form-label"></label>
                        <input type="text" id="nomeUsuario" name="nomeUsuario"
                               placeholder="Digite seu nome de usuário"
                               value="<?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>"
                               maxlength="100" required>
                    </div>

                    <div class="conjunto">
                        <label for="senha" class="form-label"></label>
                        <input type="password" id="senha" name="senha"
                               placeholder="Digite sua senha"
                               minlength="8" required>
                    </div>

                    <div class="conjunto">
                        <label for="confirmarSenha" class="form-label"></label>
                        <input type="password" id="confirmarSenha" name="confirmarSenha"
                               placeholder="Confirme sua senha"
                               minlength="8" required>
                    </div>

                    <div class="conjunto">
                        <label for="telefone" class="form-label"></label>
                        <input type="text" id="telefone" name="telefone"
                               placeholder="Digite seu telefone"
                               value="<?php echo htmlspecialchars($telefone, ENT_QUOTES, 'UTF-8'); ?>"
                               maxlength="20" required>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck">
                            <label class="form-check-label" for="gridCheck">
                                Permito o compartilhamento da minha localização
                            </label>
                        </div>
                    </div>

                    <button id="botao_login" type="submit" class="btn btn-outline-danger">
                        Cadastrar-se
                    </button>

                </form>
            </div>
        </main>
    </div>

    <footer></footer>
</body>

</html>