
<?php

include "../../infra/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
s
    $id = $_POST["id"];
    $nome_linha = $_POST["nome_linha"];
    $numero_serie = $_POST["numero_serie"];
    $velocidade = $_POST["velocidade"];
    $estado = $_POST["estado"];
    $funcionario_id = $_POST["funcionario_id"];

    // Atualiza os dados do trem
    $sql = "UPDATE trens
            SET nome_linha = ?,
                numero_serie = ?,
                velocidade = ?,
                estado = ?,
                funcionario_id = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "ssssii",
            $nome_linha,
            $numero_serie,
            $velocidade,
            $estado,
            $funcionario_id,
            $id
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: visualizar_trens.php");
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
        <h2>Editando o trem <?php echo $trem["nome_linha"]?>!</h2>
        <form action="editar_trens.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $trem["id"]?>">

            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo $trem["nome_linha"]; ?>">
            <br>

            <label for="numero_serie">Número de Série:</label>
            <input type="text" name="numero_serie" value="<?php echo $trem["numero_serie"]; ?>">
            <br>

            <label for="velocidade">Velocidade:</label>
            <input type="text" name="velocidade" value="<?php echo $trem["velocidade"]; ?>">
            <br>

            <label for="estado">Status:</label>
                <select name="estado" id="estado" required>
                    <option value="">Selecione o status:</option>

                    <option value="Ativo" <?php if ($trem["estado"] == "Ativo") echo "selected"; ?>>
                    Ativo
                    </option>

                    <option value="Inativo" <?php if ($trem["estado"] == "Inativo") echo "selected"; ?>>
                    Inativo
                    </option>

                    <option value="Manutencao" <?php if ($trem["estado"] == "Manutencao") echo "selected"; ?>>
                    Manutenção
                    </option>
                </select>

            <button type="submit">Atualizar</button>

        </form>

    </main>


</body>

</html> 