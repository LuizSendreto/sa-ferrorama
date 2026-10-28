<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include '../../infra/conexao.php';

$erro = "";

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Guardar dados digitados se houver erro
$nome = "";
$email = "";
$telefone = "";

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

    } else {

        // Criptografar a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Definir o tipo e o nível de acesso
        $tipo = "funcionario";
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

            // Retornar para a listagem
            header("Location: ../usuarios/visu_usuarios.php");
            exit;

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {
                $erro = "Já existe um usuário com esse e-mail.";
            } else {
                error_log($e->getMessage());
                $erro = "Erro ao cadastrar funcionário.";
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
    <title>Cadastrar Funcionário</title>
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

                <h2 id="titulo_cadastro">Cadastrar Funcionário</h2>

                <?php if ($erro != "") { ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php } ?>

                <form id="form_cadastro" method="POST" action="">

                    <input type="hidden" name="csrf_token"
                           value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="conjunto">
                        <input type="email" id="email" name="email"
                               placeholder="Digite seu email"
                               maxlength="100"
                               value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                               required>
                    </div>

                    <div class="conjunto">
                        <input type="text" id="nomeUsuario" name="nomeUsuario"
                               placeholder="Digite o nome do funcionário"
                               maxlength="100"
                               value="<?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>"
                               required>
                    </div>

                    <div class="conjunto">
                        <input type="password" id="senha" name="senha"
                               placeholder="Digite sua senha"
                               minlength="8"
                               required>
                    </div>

                    <div class="conjunto">
                        <input type="password" id="confirmarSenha" name="confirmarSenha"
                               placeholder="Confirme sua senha"
                               minlength="8"
                               required>
                    </div>

                    <div class="conjunto">
                        <input type="text" id="telefone" name="telefone"
                               placeholder="Digite seu telefone"
                               maxlength="20"
                               value="<?php echo htmlspecialchars($telefone, ENT_QUOTES, 'UTF-8'); ?>"
                               required>
                    </div>
                    <button id="botao_cadastro" type="submit" class="btn btn-outline-danger">
                        Cadastrar Funcionário
                    </button>
                </form>
            </div>
        </main>
    </div>

    <footer></footer>

</body>

</html>