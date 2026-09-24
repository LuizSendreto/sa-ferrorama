<?php
include "../../infra/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];

    // Atualiza os dados do usuário
    $sql = "UPDATE clientes
            SET nome = ?, email = ?, telefone = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $nome,
            $email,
            $telefone,
            $id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }

    // Volta para a visualização dos usuários
    header("Location: visu_usuario.php");
    exit();
}

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("O ID do usuário não foi informado.");
}

$id = $_GET["id"];

// Busca o usuário no banco
$sql = "SELECT * FROM clientes WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    $cliente = mysqli_fetch_assoc($resultado);

    mysqli_stmt_close($stmt);
}

// Verifica se o usuário foi encontrado
if (!$cliente) {
    die("Usuário não encontrado.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Editar Usuario</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <header>
        <h1>Editar Usuário</h1>
    </header>

    <main>
        <h2>Editando o usuario <?php echo $cliente["nome"]?>!</h2>
        <form action="editar_usuarios.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $cliente["id"]?>">

            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo $usuario["nome"]; ?>">
            <br>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $usuario["email"]; ?>">
            <br>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" value="<?php echo $usuario["telefone"]; ?>">
            <br>

            <button type="submit">Atualizar</button>

        </form>

    </main>

    <footer>

    </footer>

</body>

</html>

