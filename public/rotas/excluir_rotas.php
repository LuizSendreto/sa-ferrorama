<?php

// Apenas administradores podem excluir rotas
require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include "../../infra/conexao.php";

// Permitir exclusão somente por POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: rotas.php");
    exit;
}

// Verificar o token CSRF
if (
    !isset($_POST['csrf_token']) ||
    !is_string($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
) {
    http_response_code(403);
    exit("Solicitação inválida.");
}

// Verificar o ID recebido
if (
    !isset($_POST["id"]) ||
    !ctype_digit((string) $_POST["id"]) ||
    (int) $_POST["id"] <= 0
) {
    die("ID inválido.");
}

$id = (int) $_POST["id"];

try {

    // Excluir a rota usando prepared statement
    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM rotas WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $apagou = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($apagou == 0) {
        die("Nenhuma rota foi encontrada para exclusão.");
    }

} catch (Throwable $e) {
    error_log($e->getMessage());
    die("Erro ao excluir a rota.");
}

// Voltar para a listagem
header("Location: rotas.php");
exit;

?>