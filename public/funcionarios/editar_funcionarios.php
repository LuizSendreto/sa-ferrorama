<?php

// conexão com o banco
include "../infra/conexao.php";

// busca no banco
$id = $_GET["id"];

$sql = "SELECT * FROM funcionarios WHERE id = $id";
$resultado = mysqli_query($conexao, $sql);

$funcionario = mysqli_fetch_assoc($resultado);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strain</title>
    <link rel="stylesheet" href="style/styles.css">
</head>

<body>

    <header>
        <h1>Strain</h1>
    </header>

    <main>

        <h2>Editando o funcionário <?php echo $funcionario["nome"]; ?>!</h2>

        <form action="atualizar.php" method="POST">

            <input type="hidden" name="id" value="<?php echo $funcionario["id"]; ?>">

            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo $funcionario["nome"]; ?>">
            <br>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $funcionario["email"]; ?>">
            <br>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" value="<?php echo $funcionario["telefone"]; ?>">
            <br>

            <button type="submit">Atualizar</button>

        </form>

    </main>

    <footer>

    </footer>

</body>

</html>
