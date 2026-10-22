<?php

session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

include '../../infra/conexao.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nomeUsuario']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmarSenha'];

    if ($senha != $confirmarSenha) {
        $erro = "As senhas não são iguais.";
    } else {
        $senha = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO funcionarios (nome, email, telefone, senha) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nome, $email, $telefone, $senha);
            $stmt->execute();
            $stmt->close();

            // Volta para a lista de usuários (quem cadastra já está logado)
            header("Location: ../usuarios/visu_usuarios.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $erro = "Já existe um funcionário com esse e-mail.";
            } else {
                $erro = "Erro ao cadastrar: " . $e->getMessage();
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

                    <div class="conjunto">
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="conjunto">
                        <input type="text" id="nomeUsuario" name="nomeUsuario" placeholder="Digite o nome do funcionário" required>
                    </div>

                    <div class="conjunto">
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <div class="conjunto">
                        <input type="password" id="confirmarSenha" name="confirmarSenha" placeholder="Confirme sua senha" required>
                    </div>

                    <div class="conjunto">
                        <input type="text" id="telefone" name="telefone" placeholder="Digite seu telefone" required>
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