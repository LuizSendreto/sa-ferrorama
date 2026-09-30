<?php

session_start();

include "../../infra/conexao.php";

if (!isset($_SESSION["funcionario_id"])) {
    die("Acesso negado. Você precisa estar logado.");
}

if (!isset($_SESSION["tipo_usuario"]) || $_SESSION["tipo_usuario"] != "funcionario") {
    die("Acesso negado. Você não tem permissão para excluir funcionários.");
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Erro: funcionário não encontrado.");
}

$id = $_GET["id"];

$sql = "DELETE FROM funcionarios WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Erro ao preparar a exclusão do funcionário.");
}

if (!mysqli_stmt_bind_param($stmt, "i", $id)) {
    mysqli_stmt_close($stmt);
    die("Erro ao preparar os dados para exclusão.");
}

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    die("Erro ao excluir o funcionário. Tente novamente.");
}

if (mysqli_stmt_affected_rows($stmt) == 0) {
    mysqli_stmt_close($stmt);
    die("Nenhum funcionário foi encontrado para exclusão.");
}

mysqli_stmt_close($stmt);

header("Location: ../usuarios/visu_usuarios.php");
exit;

?>