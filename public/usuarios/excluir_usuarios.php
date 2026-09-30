<?php

session_start();

include "../../infra/conexao.php";

if (!isset($_SESSION["funcionario_id"])) {
    die("Acesso negado. Você precisa estar logado.");
}

if ($_SESSION["tipo_usuario"] != "funcionario") {
    die("Acesso negado. Você não tem permissão para excluir usuários.");
}

if (!isset($_GET["id"])) {
    die("ID do usuário não informado.");
}

$id = $_GET["id"];

$sql = "DELETE FROM clientes WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

header("Location: visu_usuarios.php");
exit;

?>