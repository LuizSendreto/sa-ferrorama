
<?php

include "../../infra/conexao.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id"];
    $nome = $_POST["nome_rota"];
    $ = $_POST["numero_serie"];
    $velocidade = $_POST["velocidade"];
    $estado = $_POST["estado"];
    $funcionario_id = $_POST["funcionario_id"];

    // Atualiza os dados do usuário
    $sql = "UPDATE trens
            SET nome = ?, numero_serie = ?, velocidade = ?, estado = ?, funcionario_id = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "siisii",
            $nome,
            $numero_serie,
            $velocidade,
            $estado,
            $funcionario_id,
            $id
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }

    // Volta para a visualização dos usuários
    header("Location: cadastrar_trens.php");
    exit();
}

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("O ID do trem não foi informado.");
}

$id = $_GET["id"];

// Busca o trem no banco
$sql = "SELECT * FROM trens WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    $trem = mysqli_fetch_assoc($resultado);

    mysqli_stmt_close($stmt);
}

// Verifica se o trem foi encontrado
if (!$trem) {
    die("Trem não encontrado.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Editar Trem</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <header>
        <h1>Editar Trem</h1>
    </header>

    <main>
        <h2>Editando a rota <?php echo $trem["nome_rota"]?>!</h2>
        <form action="editar_rotas.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $trem["id"]?>">

            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo $trem["nome_rota"]; ?>">
            <br>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao"><?php echo $trem["descricao"]; ?></textarea>
            <br>

            <label for="tempo_previsto">Tempo Previsto (minutos):</label>
            <input type="number" name="tempo_previsto" value="<?php echo $trem["tempo_previsto"]; ?>" min="0">
            <br>

            <label for="estado">Estado:</label>
            <input type="number" name="estado" value="<?php echo $trem["estado"]; ?>">
            <br>

            <button type="submit">Atualizar</button>

        </form>

    </main>

    <footer>

    </footer>

</body>

</html> 