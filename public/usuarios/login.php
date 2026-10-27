<?php

session_start();

include '../../infra/conexao.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Buscar o usuário na nova tabela
    $sql = "SELECT id, nome, email, senha, tipo, nivel_acesso
            FROM usuarios
            WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $usuario = $resultado->fetch_assoc();

        // Verificar a senha cadastrada
        if (password_verify($senha, $usuario['senha'])) {

            session_regenerate_id(true);

            // Informações do usuário logado
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['tipo_usuario'] = $usuario['tipo'];
            $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];

            // Redirecionar conforme o tipo
            if ($usuario['tipo'] == "funcionario") {

                header("Location: ../dashboard.php");
                exit;

            } else {

                header("Location: home_usuario.php");
                exit;

            }

        } else {
            $erro = "Senha incorreta.";
        }

    } else {
        $erro = "E-mail não cadastrado. Clique em 'Cadastrar-se' para criar uma conta.";
    }

    $stmt->close();
}

?>

<html class="login" lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazer Login</title>
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
                <h2 id="titulo_login">Fazer Login</h2>

                <?php if ($erro != "") { ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php } ?>

                <form id="form_login" method="POST" action="">
                    <div class="conjunto">
                        <label for="email" class="form-label"></label>
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="conjunto">
                        <label for="senha" class="form-label"></label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <button id="botao_login" type="submit" class="btn btn-outline-danger">
                        Entrar
                    </button>
                </form>
                <div class="Cadastro">
                    <p id="texto_login"> Você ainda não possui cadastro?</p>
                    <a class="link1" href="../../public/usuarios/cadastro_usuarios.php">Cadastrar-se!</a>
                </div>

            </div>
        </main>
    </div>

    <footer></footer>

</body>

</html>
