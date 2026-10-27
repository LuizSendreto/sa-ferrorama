<?php

// Permitir exclusão somente para administradores
require_once '../../infra/verificar_acesso.php';
verificarAdmin();

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
    exit("Solicitação inválida.");
}

// Validar ID
if (
    !isset($_POST["id"]) ||
    !is_string($_POST["id"]) ||
    !ctype_digit($_POST["id"]) ||
    (int) $_POST["id"] <= 0
) {
    die("ID inválido.");
}

$id = (int) $_POST["id"];

// Impedir que o administrador exclua a própria conta
if ($id == (int) $_SESSION["usuario_id"]) {
    die("Você não pode excluir sua própria conta.");
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
        die("Usuário não encontrado.");
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
        die("Este funcionário é responsável por um trem. Altere o responsável antes de excluir.");
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
        die("Nenhum usuário foi encontrado para exclusão.");
    }

} catch (Throwable $e) {
    error_log($e->getMessage());
    die("Erro ao excluir o usuário.");
}

// Voltar para a visualização
header("Location: visu_usuarios.php");
exit;

?>