<?php

session_start();

include "../../infra/conexao.php";

if (!isset($_SESSION["funcionario_id"])) {
    die("Acesso negado. Você precisa estar logado.");
}

if (!isset($_SESSION["tipo_usuario"]) || $_SESSION["tipo_usuario"] != "funcionario") {
    die("Acesso negado. Você não tem permissão para excluir funcionários.");
}

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    die("Erro: funcionário não encontrado.");
}

$id = (int) $_GET["id"];

// Não deixa o funcionário excluir a própria conta enquanto está logado
if ($id == (int) $_SESSION["funcionario_id"]) {
    echo "<script>
            alert('Você não pode excluir a sua própria conta enquanto está logado.');
            window.location.href = '../usuarios/visu_usuarios.php';
          </script>";
    exit;
}

try {
    // Não deixa excluir funcionário que ainda é responsável por trens
    $stmt_check = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM trens WHERE funcionario_id = ?");
    mysqli_stmt_bind_param($stmt_check, "i", $id);
    mysqli_stmt_execute($stmt_check);
    $total = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check))["total"];
    mysqli_stmt_close($stmt_check);

    if ($total > 0) {
        echo "<script>
                alert('Não é possível excluir: este funcionário é responsável por $total trem(ns). Troque o responsável desses trens primeiro.');
                window.location.href = '../usuarios/visu_usuarios.php';
              </script>";
        exit;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM funcionarios WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $apagou = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($apagou == 0) {
        die("Nenhum funcionário foi encontrado para exclusão.");
    }
} catch (Throwable $e) {
    die("Erro ao excluir o funcionário: " . htmlspecialchars($e->getMessage()));
}

header("Location: ../usuarios/visu_usuarios.php");
exit;

?>