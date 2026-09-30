<?php
// codigo para excluir um usuário do banco de dados com prepared statements revisado.
session_start();

include "../../infra/conexao.php";

if (!isset($_SESSION["funcionario_id"])) {
    die("Acesso negado. Você precisa estar logado.");
}

if (!isset($_SESSION["tipo_usuario"]) || $_SESSION["tipo_usuario"] != "funcionario") {
    die("Acesso negado. Você não tem permissão para excluir usuários.");
}

// Verifica se o ID foi informado
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Erro: usuário não encontrado.");
}

$id = $_GET["id"];

$sql = "DELETE FROM clientes WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Erro ao preparar a exclusão do usuário.");
}

// Define o parâmetro como inteiro
if (!mysqli_stmt_bind_param($stmt, "i", $id)) {
    mysqli_stmt_close($stmt);
    die("Erro ao preparar os dados para exclusão.");
}

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    die("Erro ao excluir o usuário. Tente novamente.");
}

if (mysqli_stmt_affected_rows($stmt) == 0) {
    mysqli_stmt_close($stmt);
    die("Nenhum usuário foi encontrado para exclusão.");
}

mysqli_stmt_close($stmt);

header("Location: visu_usuarios.php");
exit;

?>