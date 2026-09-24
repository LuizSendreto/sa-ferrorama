<?php

include '../../infra/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nomeUsuario'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmarSenha'];

    if ($senha != $confirmarSenha) {
        die("As senhas não são iguais.");
    }

    $senha = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO funcionarios (nome, email, telefone, senha) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssss", $nome, $email, $telefone, $senha);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../usuarios/visu_usuarios.php");
        exit;
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html class="login" lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar</title>

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

                <form id="form_cadastro" method="POST" action="">

                    <div class="conjunto">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Digite seu email"
                            required>
                    </div>

                    <div class="conjunto">
                        <input
                            type="text"
                            id="nomeUsuario"
                            name="nomeUsuario"
                            placeholder="Digite seu nome de usuário"
                            required>
                    </div>

                    <div class="conjunto">
                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Digite sua senha"
                            required>
                    </div>

                    <div class="conjunto">
                        <input
                            type="password"
                            id="confirmarSenha"
                            name="confirmarSenha"
                            placeholder="Confirme sua senha"
                            required>
                    </div>

                    <div class="conjunto">
                        <input
                            type="text"
                            id="telefone"
                            name="telefone"
                            placeholder="Digite seu telefone"
                            required>
                    </div>

                    <div class="col-12">
                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="gridCheck">

                            <label class="form-check-label" for="gridCheck">
                                Se for um funcionário, selecione esta opção.
                            </label>

                            <br>

                        </div>
                    </div>

                    <button
                        id="botao_cadastro"
                        type="submit"
                        class="btn btn-outline-danger">

                        Cadastrar-se

                    </button>

                </form>

            </div>

        </main>

    </div>

    <footer></footer>

    <script src="script.js"></script>

</body>

</html>
```
