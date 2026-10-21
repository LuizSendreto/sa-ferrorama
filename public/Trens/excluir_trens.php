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

try {
    // Impede excluir trem que ainda tem rotas vinculadas
    $stmt_check = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM rotas WHERE trem_id = ?");
    mysqli_stmt_bind_param($stmt_check, "i", $id);
    mysqli_stmt_execute($stmt_check);
    $total = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check))["total"];
    mysqli_stmt_close($stmt_check);

    if ($total > 0) {
        echo "<script>
                alert('Não é possível excluir: este trem possui $total rota(s) vinculada(s). Exclua ou altere as rotas primeiro.');
                window.location.href = 'visualizar_trens.php';
              </script>";
        exit;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM trens WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $apagou = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($apagou == 0) {
        die("Nenhum trem foi encontrado para exclusão.");
    }
} catch (Throwable $e) {
    die("Erro ao excluir o trem: " . htmlspecialchars($e->getMessage()));
}

header("Location: visualizar_trens.php");
exit;

?>