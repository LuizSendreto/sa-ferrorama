<?php

// Permitir exclusão somente para administradores
require_once '../../infra/verificar_acesso.php';
verificarAdmin();
require_once __DIR__ . '/../componentes/resposta_modal.php';

include "../../infra/conexao.php";

// Aceitar apenas requisições POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: visu_usuarios.php");
    exit;
}

// Verificar token CSRF
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

// Validar ID
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

// Impedir que o administrador exclua a própria conta
if ($id == (int) $_SESSION["usuario_id"]) {
    strainRespostaModal(false, "Você não pode excluir sua própria conta.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Você não pode excluir sua própria conta.");
}

try {

    // Verificar se o usuário existe
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, tipo FROM usuarios WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $usuario = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );

    mysqli_stmt_close($stmt);

    if (!$usuario) {
        strainRespostaModal(false, "Usuário não encontrado.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Usuário não encontrado.");
    }

    // Verificar se o funcionário é responsável por algum trem
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id FROM trens WHERE funcionario_id = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $possui_trem = mysqli_num_rows(
        mysqli_stmt_get_result($stmt)
    ) > 0;

    mysqli_stmt_close($stmt);

    if ($possui_trem) {
        strainRespostaModal(false, "Este funcionário é responsável por um trem. Altere o responsável antes de excluir.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Este funcionário é responsável por um trem. Altere o responsável antes de excluir.");
    }

    // Excluir o usuário da tabela unificada
    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM usuarios WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $apagou = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($apagou == 0) {
        strainRespostaModal(false, "Nenhum usuário foi encontrado para exclusão.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Nenhum usuário foi encontrado para exclusão.");
    }

} catch (Throwable $e) {
    error_log($e->getMessage());
    strainRespostaModal(false, "Erro ao excluir o usuário.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Erro ao excluir o usuário.");
}

// Voltar para a visualização
strainRespostaModal(true, "Operação concluída.");
header("Location: visu_usuarios.php");
exit;

?>