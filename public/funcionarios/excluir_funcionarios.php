<?php

include "../../infra/conexao.php";

if (!isset($_GET["id"])) {
    die("ID do funcionário não informado.");
}

$id = $_GET["id"];

$sql = "DELETE FROM funcionarios WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

header("Location: ../usuarios/visu_usuarios.php");
exit;

?>