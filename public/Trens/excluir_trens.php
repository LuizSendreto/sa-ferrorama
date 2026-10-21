<?php
// Exclui um trem do banco de dados (com prepared statements).
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

// Impede excluir trem que ainda tem rotas vinculadas
$stmt_check = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM rotas WHERE trem_id = ?");
mysqli_stmt_bind_param($stmt_check, "i", $id);
mysqli_stmt_execute($stmt_check);
$total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check))["total"];
mysqli_stmt_close($stmt_check);

if ($total > 0) {
    echo "<script>
            alert('Não é possível excluir: este trem possui $total rota(s) vinculada(s). Exclua ou altere as rotas primeiro.');
            window.location.href = 'visualizar_trens.php';
          </script>";
    exit;
}

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

header("Location: visualizar_trens.php");
exit;

?>