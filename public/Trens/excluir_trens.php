<?php
// Exclui um trem e TAMBÉM as rotas vinculadas a ele.
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
    mysqli_begin_transaction($conn);

    // 1) apaga as rotas desse trem
    $stmt = mysqli_prepare($conn, "DELETE FROM rotas WHERE trem_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 2) apaga o trem
    $stmt = mysqli_prepare($conn, "DELETE FROM trens WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $apagou = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);

    if ($apagou == 0) {
        die("Nenhum trem foi encontrado para exclusão.");
    }
} catch (Throwable $e) {
    mysqli_rollback($conn);
    die("Erro ao excluir o trem: " . htmlspecialchars($e->getMessage()));
}

header("Location: visualizar_trens.php");
exit;

?>