
<?php

// Apenas administradores podem excluir trens
require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include "../../infra/conexao.php";

// Aceitar exclusões apenas por POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: visualizar_trens.php");
    exit;
}

// Verificar o token de segurança
if (
    !isset($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
) {
    http_response_code(403);
    exit("Solicitação inválida.");
}

// Verificar o ID do trem
if (!isset($_POST["id"]) || !ctype_digit($_POST["id"]) || (int) $_POST["id"] <= 0) {
    die("ID inválido.");
}

$id = (int) $_POST["id"];

// Fazer o MySQL informar erros nas operações
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    mysqli_begin_transaction($conn);

    // 1. Excluir as rotas vinculadas ao trem
    $stmt = mysqli_prepare($conn, "DELETE FROM rotas WHERE trem_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 2. Excluir o trem
    $stmt = mysqli_prepare($conn, "DELETE FROM trens WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $apagou = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($apagou == 0) {
        mysqli_rollback($conn);
        die("Nenhum trem foi encontrado para exclusão.");
    }

    mysqli_commit($conn);

} catch (Throwable $e) {
    mysqli_rollback($conn);
    error_log($e->getMessage());
    die("Erro ao excluir o trem.");
}

// Voltar para a visualização
header("Location: visualizar_trens.php");
exit;

?>
