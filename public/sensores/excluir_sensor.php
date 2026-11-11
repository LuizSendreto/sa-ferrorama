<?php

// Permitir exclusão somente para administradores
require_once '../../infra/verificar_acesso.php';
verificarAdmin();
require_once __DIR__ . '/../componentes/resposta_modal.php';

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
    strainRespostaModal(false, "Solicitação inválida.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Solicitação inválida.");
}

// Verificar o ID do sensor
if (
    !isset($_POST["id"]) ||
    !is_string($_POST["id"]) ||
    !ctype_digit($_POST["id"]) ||
    (int) $_POST["id"] <= 0
) {
    strainRespostaModal(false, "ID inválido.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("ID inválido.");
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
        strainRespostaModal(false, "Nenhum sensor encontrado para exclusão.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Nenhum sensor encontrado para exclusão.");
    }

} catch (Throwable $e) {
    error_log($e->getMessage());
    strainRespostaModal(false, "Erro ao excluir o sensor.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Erro ao excluir o sensor.");
}

// Voltar para a página de visualização
strainRespostaModal(true, "Operação concluída.");
header("Location: visu_sensores.php");
exit;

?>