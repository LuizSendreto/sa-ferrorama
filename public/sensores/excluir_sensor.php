<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

// conexão com o banco
include "../../infra/conexao.php";

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    die("ID inválido.");
}

$id = (int) $_GET["id"];

try {
    // Prepared Statement
    $stmt = mysqli_prepare($conn, "DELETE FROM sensores WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} catch (Throwable $e) {
    die("Erro ao excluir o sensor: " . htmlspecialchars($e->getMessage()));
}

header("Location: visu_sensores.php");
exit;

?>