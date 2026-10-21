<?php
// Exclui uma rota do banco de dados (com prepared statements).
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

include "../../infra/conexao.php";

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    die("ID inválido.");
}

$id = (int) $_GET["id"];

try {
    $stmt = mysqli_prepare($conn, "DELETE FROM rotas WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $apagou = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($apagou == 0) {
        die("Nenhuma rota foi encontrada para exclusão.");
    }
} catch (Throwable $e) {
    die("Erro ao excluir a rota: " . htmlspecialchars($e->getMessage()));
}

header("Location: rotas.php");
exit;

?>