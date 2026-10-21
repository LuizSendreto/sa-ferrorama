<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

include "../../infra/conexao.php";

$erro = "";
$enviado = null; // guarda o que foi digitado, se der erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id             = (int) ($_POST["id"] ?? 0);
    $nome_rota      = trim($_POST["nome_rota"] ?? "");
    $descricao      = trim($_POST["descricao"] ?? "");
    $distancia      = trim($_POST["distancia"] ?? "");
    $tempo_estimado = trim($_POST["tempo_estimado"] ?? "");
    $trem_id        = $_POST["trem_id"] ?? "";

    $enviado = [
        "nome_rota"      => $nome_rota,
        "descricao"      => $descricao,
        "distancia"      => $distancia,
        "tempo_estimado" => $tempo_estimado,
        "trem_id"        => $trem_id,
    ];

    $distancia_num = (float) str_replace(",", ".", $distancia);

    if ($nome_rota === "" || $descricao === "") {
        $erro = "Preencha o nome e a descrição da rota.";
    } elseif (strlen($descricao) > 255) {
        $erro = "A descrição pode ter no máximo 255 caracteres.";
    } elseif ($distancia_num <= 0 || $distancia_num > 9999.99) {
        $erro = "Informe uma distância entre 0,01 e 9999,99 km.";
    } elseif (!ctype_digit($tempo_estimado) || (int) $tempo_estimado <= 0) {
        $erro = "O tempo estimado deve ser um número inteiro maior que zero.";
    } elseif (!ctype_digit((string) $trem_id) || (int) $trem_id <= 0) {
        $erro = "Selecione um trem.";
    } else {
        $tempo_int = (int) $tempo_estimado;
        $trem_int  = (int) $trem_id;

        try {
            $sql = "UPDATE rotas
                    SET nome_rota = ?, descricao = ?, distancia = ?,
                        tempo_estimado = ?, trem_id = ?
                    WHERE id = ?";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ssdiii",
                $nome_rota,
                $descricao,
                $distancia_num,
                $tempo_int,
                $trem_int,
                $id
            );

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header("Location: rotas.php");
            exit();
        } catch (Throwable $e) {
            $erro = "Erro no banco de dados: " . $e->getMessage();
        }
    }
} else {
    $id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
}

if ($id <= 0) {
    die("O ID da rota não foi informado.");
}

// Busca a rota no banco
$stmt = mysqli_prepare($conn, "SELECT * FROM rotas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$rota = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$rota) {
    die("Rota não encontrada.");
}

$titulo_rota = $rota["nome_rota"];

// Se deu erro ao salvar, mostra de novo o que a pessoa tinha digitado
if ($enviado !== null) {
    $rota = array_merge($rota, $enviado);
}

$trens = mysqli_query($conn, "SELECT id, nome_linha, numero_serie FROM trens ORDER BY nome_linha ASC");

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rota</title>
    <link rel="stylesheet" href="../../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <header>
        <h1>Editar Rota</h1>
    </header>

    <main>
        <h2>Editando a rota <?php echo htmlspecialchars($titulo_rota); ?>!</h2>

        <?php if ($erro != "") { ?>
            <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
        <?php } ?>

        <form action="editar_rotas.php" method="POST">
            <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

            <label for="nome_rota">Nome da rota:</label>
            <input type="text" id="nome_rota" name="nome_rota"
                   value="<?php echo htmlspecialchars($rota["nome_rota"]); ?>" required>
            <br>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" maxlength="255" required><?php echo htmlspecialchars($rota["descricao"]); ?></textarea>
            <br>

            <label for="distancia">Distância (km):</label>
            <input type="number" id="distancia" name="distancia" min="0.01" max="9999.99" step="0.01"
                   value="<?php echo htmlspecialchars((string) $rota["distancia"]); ?>" required>
            <br>

            <label for="tempo_estimado">Tempo estimado (minutos):</label>
            <input type="number" id="tempo_estimado" name="tempo_estimado" min="1" step="1"
                   value="<?php echo htmlspecialchars((string) $rota["tempo_estimado"]); ?>" required>
            <br>

            <label for="trem_id">Trem:</label>
            <select name="trem_id" id="trem_id" required>
                <option value="">Selecione o trem</option>
                <?php while ($t = mysqli_fetch_assoc($trens)) { ?>
                    <option value="<?php echo (int) $t["id"]; ?>"
                        <?php if ((string) $t["id"] === (string) $rota["trem_id"]) echo "selected"; ?>>
                        <?php echo htmlspecialchars($t["numero_serie"] . " - " . $t["nome_linha"]); ?>
                    </option>
                <?php } ?>
            </select>
            <br>

            <button type="submit">Atualizar</button>
            <a href="rotas.php">Cancelar</a>
        </form>
    </main>

</body>

</html>