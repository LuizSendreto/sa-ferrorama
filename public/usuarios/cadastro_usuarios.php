<?php

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
            $sql = "INSERT INTO clientes (nome, email, telefone, senha) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nome, $email, $telefone, $senha);
            $stmt->execute();
            $stmt->close();

            header("Location: login.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $erro = "Já existe uma conta com esse e-mail.";
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
                    <div class="conjunto">
                        <label for="email" class="form-label"></label>
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="conjunto">
                        <label for="nomeUsuario" class="form-label"></label>
                        <input type="text" id="nomeUsuario" name="nomeUsuario" placeholder="Digite seu nome de usuário" required>
                    </div>

                    <div class="conjunto">
                        <label for="senha" class="form-label"></label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>

                    <div class="conjunto">
                        <label for="confirmarSenha" class="form-label"></label>
                        <input type="password" id="confirmarSenha" name="confirmarSenha" placeholder="Confirme sua senha" required>
                    </div>

                    <div class="conjunto">
                        <label for="telefone" class="form-label"></label>
                        <input type="text" id="telefone" name="telefone" placeholder="Digite seu telefone" required>
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