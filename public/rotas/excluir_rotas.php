<?php
// codigo para excluir uma rota do banco de dados com prepared statements revisado.
session_start();

include "../../infra/conexao.php";

$id = $_GET["id"];

$sql = "DELETE FROM rotas WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Erro ao preparar a exclusão da rota.");
}

// Define o parâmetro como inteiro
if (!mysqli_stmt_bind_param($stmt, "i", $id)) {
    mysqli_stmt_close($stmt);
    die("Erro ao preparar os dados para exclusão.");
}

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    die("Erro ao excluir a rota. Tente novamente.");
}

if (mysqli_stmt_affected_rows($stmt) == 0) {
    mysqli_stmt_close($stmt);
    die("Nenhuma rota foi encontrado para exclusão.");
}

mysqli_stmt_close($stmt);

header("Location: rotas.php");
exit;

?>