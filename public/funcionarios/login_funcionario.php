<?php

session_start();

include '../../infra/conexao.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Procura o funcionário pelo e-mail
    $sql = "SELECT id, nome, email, senha FROM funcionarios WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    // Verifica se o e-mail foi encontrado
    if ($resultado->num_rows == 0) {

        $erro = "E-mail não cadastrado.";

    } else {

        $funcionario = $resultado->fetch_assoc();

        // Verifica se a senha digitada é igual à senha cadastrada
        if (password_verify($senha, $funcionario['senha'])) {

            // Cria as informações da sessão
            $_SESSION['funcionario_id'] = $funcionario['id'];
            $_SESSION['funcionario_nome'] = $funcionario['nome'];
            $_SESSION['funcionario_email'] = $funcionario['email'];

            // Vai para o dashboard
            header("Location: ../dashboard.php");
            exit;

        } else {

            $erro = "Senha incorreta.";
        }
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Strain</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="../../assets/style/style.css">

</head>

<body class="body_telalogin">

    <div class="Strain">

        <img
            class="Strain"
            src="../../assets/img/Tela de login atualizada.png"
            alt="Strain">

    </div>

    <div class="flex">

        <main class="blue">

            <div class="container_login">

                <h2>Entrar</h2>

                <?php if ($erro != "") { ?>

                    <div class="alert alert-danger">
                        <?php echo $erro; ?>
                    </div>

                <?php } ?>

                <form method="POST">

                    <div class="conjunto">

                        <input
                            type="email"
                            name="email"
                            placeholder="Digite seu email"
                            required>

                    </div>

                    <div class="conjunto">

                        <input
                            type="password"
                            name="senha"
                            placeholder="Digite sua senha"
                            required>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-outline-danger">

                        Entrar

                    </button>

                </form>

            </div>

        </main>

    </div>

</body>

</html>