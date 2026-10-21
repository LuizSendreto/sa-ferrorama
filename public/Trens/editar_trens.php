<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

include "../../infra/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id             = (int) $_POST["id"];
    $nome_linha     = trim($_POST["nome_linha"]);
    $numero_serie   = trim($_POST["numero_serie"]);
    $velocidade     = (int) $_POST["velocidade"];
    $estado         = $_POST["estado"];
    $funcionario_id = (int) $_POST["funcionario_id"];

    $estados_validos = ["Ativo", "Inativo", "Manutencao"];

    if ($nome_linha === "" || $numero_serie === "") {
        $erro = "Preencha todos os campos.";
    } elseif ($velocidade < 0) {
        $erro = "A velocidade não pode ser negativa.";
    } elseif (!in_array($estado, $estados_validos)) {
        $erro = "Status inválido.";
    } elseif ($funcionario_id <= 0) {
        $erro = "Selecione um funcionário.";
    } else {
        $sql = "UPDATE trens
                SET nome_linha = ?, numero_serie = ?, velocidade = ?,
                    estado = ?, funcionario_id = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssisii",
            $nome_linha,
            $numero_serie,
            $velocidade,
            $estado,
            $funcionario_id,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: visualizar_trens.php");
            exit();
        } else {
            $erro = "Erro ao atualizar: " . mysqli_stmt_error($stmt);
        }
    }
} else {
    $id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
}

if ($id <= 0) {
    die("O ID do trem não foi informado.");
}

// Busca o trem no banco
$stmt = mysqli_prepare($conn, "SELECT * FROM trens WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$trem = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$trem) {
    die("Trem não encontrado.");
}

$funcionarios = mysqli_query($conn, "SELECT id, nome FROM funcionarios ORDER BY nome ASC");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Trem</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <header>
        <h1>Editar Trem</h1>
    </header>

    <main>
        <h2>Editando o trem <?php echo htmlspecialchars($trem["nome_linha"]); ?>!</h2>

        <?php if ($erro != "") { ?>
            <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
        <?php } ?>

        <form action="editar_trens.php" method="POST">
            <input type="hidden" name="id" value="<?php echo (int) $trem["id"]; ?>">

            <label for="nome_linha">Nome da linha:</label>
            <input type="text" id="nome_linha" name="nome_linha"
                   value="<?php echo htmlspecialchars($trem["nome_linha"]); ?>" required>
            <br>

            <label for="numero_serie">Número de Série:</label>
            <input type="text" id="numero_serie" name="numero_serie"
                   value="<?php echo htmlspecialchars($trem["numero_serie"]); ?>" required>
            <br>

            <label for="velocidade">Velocidade:</label>
            <input type="number" id="velocidade" name="velocidade" min="0" step="1"
                   value="<?php echo (int) $trem["velocidade"]; ?>" required>
            <br>

            <label for="estado">Status:</label>
            <select name="estado" id="estado" required>
                <option value="">Selecione o status:</option>
                <option value="Ativo" <?php if ($trem["estado"] == "Ativo") echo "selected"; ?>>Ativo</option>
                <option value="Inativo" <?php if ($trem["estado"] == "Inativo") echo "selected"; ?>>Inativo</option>
                <option value="Manutencao" <?php if ($trem["estado"] == "Manutencao") echo "selected"; ?>>Manutenção</option>
            </select>
            <br>

            <label for="funcionario_id">Funcionário responsável:</label>
            <select name="funcionario_id" id="funcionario_id" required>
                <option value="">Selecione o funcionário:</option>
                <?php while ($f = mysqli_fetch_assoc($funcionarios)) { ?>
                    <option value="<?php echo (int) $f["id"]; ?>"
                        <?php if ($f["id"] == $trem["funcionario_id"]) echo "selected"; ?>>
                        <?php echo htmlspecialchars($f["nome"]); ?>
                    </option>
                <?php } ?>
            </select>
            <br>

            <button type="submit">Atualizar</button>
        </form>
    </main>

</body>

</html>