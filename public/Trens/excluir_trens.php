<?php
// codigo para excluir um trem do banco de dados com prepared statements revisado.
session_start();

include "../../infra/conexao.php";

$id = $_GET["id"];

$sql = "DELETE FROM trens WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Erro ao preparar a exclusão do trem.");
}

// Define o parâmetro como inteiro
if (!mysqli_stmt_bind_param($stmt, "i", $id)) {
    mysqli_stmt_close($stmt);
    die("Erro ao preparar os dados para exclusão.");
}

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    die("Erro ao excluir o trem. Tente novamente.");
}

if (mysqli_stmt_affected_rows($stmt) == 0) {
    mysqli_stmt_close($stmt);
    die("Nenhum trem foi encontrado para exclusão.");
}

mysqli_stmt_close($stmt);

header("Location: cadastrar_trens.php");
exit;

?>