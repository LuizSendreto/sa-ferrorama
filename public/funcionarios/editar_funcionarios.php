<?php

include "../../infra/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];

    $sql = "UPDATE funcionarios 
            SET nome = ?, email = ?, telefone = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "sssi", $nome, $email, $telefone, $id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    header("Location: ../usuarios/visu_usuarios.php");
    exit;
}

if (!isset($_GET["id"])) {
    die("ID do funcionário não informado.");
}

$id = $_GET["id"];

$sql = "SELECT * FROM funcionarios WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

$funcionario = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

if (!$funcionario) {
    die("Funcionário não encontrado.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strain</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
</head>

<body>

    <header>
        <h1>Strain</h1>
    </header>

    <main>

        <h2>Editando o funcionário <?php echo $funcionario["nome"]; ?>!</h2>

        <form action="editar_funcionarios.php" method="POST">

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

    <footer></footer>

</body>

</html>