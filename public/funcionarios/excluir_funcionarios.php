<?php
// conexão com o banco
include "../../infra/conexao.php";

$id = $_GET["id"];

// Prepared Statement
$sql = "DELETE FROM funcionarios WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

// Associa o ID ao parâmetro
mysqli_stmt_bind_param($stmt, "i", $id);

// Executa a consulta
mysqli_stmt_execute($stmt);

// Fecha o statement
mysqli_stmt_close($stmt);

header("Location: visu_usuarios.php");
exit;

?>
