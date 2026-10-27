<?php

// Permitir exclusão somente para administradores
require_once '../../infra/verificar_acesso.php';
verificarAdmin();

// Conexão com o banco
include "../../infra/conexao.php";

// Permitir apenas requisições POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: visu_sensores.php");
    exit;
}

// Verificar o token CSRF
if (
    !isset($_POST["csrf_token"]) ||
    !is_string($_POST["csrf_token"]) ||
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
) {
    http_response_code(403);
    exit("Solicitação inválida.");
}

// Verificar o ID do sensor
if (
    !isset($_POST["id"]) ||
    !is_string($_POST["id"]) ||
    !ctype_digit($_POST["id"]) ||
    (int) $_POST["id"] <= 0
) {
    die("ID inválido.");
}

$id = (int) $_POST["id"];

try {

    // Excluir o sensor utilizando Prepared Statement
    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM sensores WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    // Verificar se algum sensor foi excluído
    $apagou = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($apagou == 0) {
        die("Nenhum sensor encontrado para exclusão.");
    }

} catch (Throwable $e) {
    error_log($e->getMessage());
    die("Erro ao excluir o sensor.");
}

// Voltar para a página de visualização
header("Location: visu_sensores.php");
exit;

?>