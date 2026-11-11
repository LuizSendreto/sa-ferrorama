<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();
require_once __DIR__ . '/../componentes/visual.php';
require_once __DIR__ . '/../componentes/resposta_modal.php';

include "../../infra/conexao.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$erro = "";
$enviado = null;

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Receber o ID do usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) ($_POST["id"] ?? 0);
} else {
    $id = (int) ($_GET["id"] ?? 0);
}

if ($id <= 0) {
    strainRespostaModal(false, "Registro inválido.", 400);
    header("Location: visu_usuarios.php");
    exit;
}

// NOVO: buscar o usuário e seu CEP
$stmt = mysqli_prepare(
    $conn,
    "SELECT
        u.id,
        u.nome,
        u.email,
        u.telefone,
        u.tipo,
        u.nivel_acesso,
        e.cep
     FROM usuarios AS u
     LEFT JOIN enderecos_usuarios AS e
        ON e.usuario_id = u.id
     WHERE u.id = ?"
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

$titulo_nome = $usuario["nome"];

// Processar edição
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar token CSRF
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !hash_equals(
            $_SESSION['csrf_token'],
            $_POST['csrf_token']
        )
    ) {
        http_response_code(403);
        strainRespostaModal(false, "Solicitação inválida.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Solicitação inválida.");
    }

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $tipo = $_POST["tipo"] ?? "";
    $nivel_acesso = $_POST["nivel_acesso"] ?? "";

    // NOVO: receber o número do CEP
    $cep = trim($_POST["cep"] ?? "");

    $enviado = [
        "nome" => $nome,
        "email" => $email,
        "telefone" => $telefone,
        "tipo" => $tipo,
        "nivel_acesso" => $nivel_acesso,
        "cep" => $cep
    ];

    if ($nome === "" || $email === "" || $telefone === "") {
        $erro = "Preencha todos os campos.";

    } elseif (mb_strlen($nome) > 100) {
        $erro = "O nome pode ter no máximo 100 caracteres.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $erro = "E-mail inválido.";

    } elseif (strlen($telefone) > 20) {
        $erro = "Telefone inválido.";

    } elseif (!in_array($tipo, ["cliente", "funcionario"], true)) {
        $erro = "Tipo de usuário inválido.";

    } elseif (!in_array($nivel_acesso, ["comum", "admin"], true)) {
        $erro = "Nível de acesso inválido.";

    } elseif ($tipo == "cliente" && $nivel_acesso == "admin") {
        $erro = "Clientes não podem possuir acesso administrativo.";

    } elseif (
        $id == (int) $_SESSION['usuario_id'] &&
        ($tipo != "funcionario" || $nivel_acesso != "admin")
    ) {
        $erro = "Você não pode remover sua própria permissão administrativa.";

    // NOVO: validar apenas o formato do CEP
    } elseif (
        $cep !== "" &&
        !preg_match('/^\d{5}-?\d{3}$/D', $cep)
    ) {
        $erro = "CEP inválido. Digite 8 números.";

    } else {

        $transacaoIniciada = false;

        try {

            // Verificar se o usuário é responsável por algum trem
            $stmt = mysqli_prepare(
                $conn,
                "SELECT id
                 FROM trens
                 WHERE funcionario_id = ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);

            $possui_trem = mysqli_num_rows(
                mysqli_stmt_get_result($stmt)
            ) > 0;

            mysqli_stmt_close($stmt);

            if ($possui_trem && $tipo != "funcionario") {

                $erro = "Este usuário é responsável por um trem e não pode ser alterado para cliente.";

            } else {

                // NOVO: preparar CEP para atualização
                $cepNumerico = str_replace("-", "", $cep);

                $cepAnterior = preg_replace(
                    '/\D/',
                    '',
                    (string) ($usuario['cep'] ?? '')
                );

                // Iniciar transação
                $conn->begin_transaction();
                $transacaoIniciada = true;

                // Atualizar dados na tabela usuarios
                $sql = "UPDATE usuarios
                        SET nome = ?,
                            email = ?,
                            telefone = ?,
                            tipo = ?,
                            nivel_acesso = ?
                        WHERE id = ?";

                $stmt = mysqli_prepare($conn, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssi",
                    $nome,
                    $email,
                    $telefone,
                    $tipo,
                    $nivel_acesso,
                    $id
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // NOVO: cadastrar ou editar somente o CEP
                if (
                    $cepNumerico !== "" &&
                    $cepNumerico !== $cepAnterior
                ) {

                    $sqlCep = "INSERT INTO enderecos_usuarios
                        (usuario_id, cep, logradouro, bairro, cidade, uf)
                        VALUES (?, ?, '', '', '', '')

                        ON DUPLICATE KEY UPDATE
                            cep = ?";

                    $stmtCep = mysqli_prepare($conn, $sqlCep);

                    mysqli_stmt_bind_param(
                        $stmtCep,
                        "iss",
                        $id,
                        $cepNumerico,
                        $cepNumerico
                    );

                    mysqli_stmt_execute($stmtCep);
                    mysqli_stmt_close($stmtCep);
                }

                // Confirmar alterações
                $conn->commit();
                $transacaoIniciada = false;

                // Atualizar sessão se editar a própria conta
                if ($id == (int) $_SESSION['usuario_id']) {
                    $_SESSION['usuario_nome'] = $nome;
                    $_SESSION['usuario_email'] = $email;
                }

                strainRespostaModal(true, "Operação concluída.");

                header("Location: visu_usuarios.php");
                exit;
            }

        } catch (mysqli_sql_exception $e) {

            if ($transacaoIniciada) {
                $conn->rollback();
            }

            if ($e->getCode() == 1062) {
                $erro = "Já existe um usuário com esse e-mail.";
            } else {
                error_log($e->getMessage());
                $erro = "Erro ao atualizar o usuário.";
            }
        }
    }
}

// Manter os dados digitados em caso de erro
if ($enviado !== null) {
    $usuario = array_merge($usuario, $enviado);
}

// Erros permanecem no modal, mantendo os dados preenchidos.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $erro !== '') {
    strainRespostaModal(false, $erro, 422);
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário ● Strain</title>
    
<link rel="stylesheet" href="../../assets/style/listagens.css?v=4">
<link rel="stylesheet" href="../../assets/style/admin_paginas.css?v=4">
<script src="../../Scripts/listagens.js?v=2" defer></script>
</head>
<body class="strain-listagem strain-admin">
    <?php strainNavegacao('Usuários', '../', $_SESSION['usuario_nome'] ?? 'Funcionário', ($_SESSION['nivel_acesso'] ?? '') === 'admin'); ?>

    <main class="strain-conteudo">
        <section class="painel_formulario">
<a class="strain-btn strain-neutro" style="margin-bottom:20px" href="../usuarios/visu_usuarios.php">Voltar para usuários</a>
            <h1 class="titulo_formulario">Editar Usuário</h1>
            <p class="subtitulo_formulario">
                Editando o usuário: <?= htmlspecialchars($titulo_nome, ENT_QUOTES, 'UTF-8') ?>
            </p>

            <?php if ($erro !== '') { ?>
                <div class="mensagem_erro_formulario" role="alert">
                    <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php } ?>

            <form action="editar_usuarios.php" method="POST" class="formulario_strain">
                <input type="hidden" name="id" value="<?= (int) $id ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <div class="campo_formulario">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" maxlength="100"
                           value="<?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                
                <div class="campo_formulario">
                    <label for="email_edicao">E-mail</label>

                    <input
                        type="email"
                        id="email_edicao"
                        name="email"
                        maxlength="100"
                        value="<?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                        >
                </div>


                <div class="campo_formulario">
                    <label for="telefone_edicao">Telefone</label>

                    <input
                        type="text"
                        id="telefone_edicao"
                        name="telefone"
                        maxlength="20"
                        value="<?= htmlspecialchars($usuario['telefone'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                        >
</div>

                <div class="campo_formulario">
                    <label for="cep">CEP (opcional)</label>
                    <input type="text" id="cep" name="cep" maxlength="9" inputmode="numeric"
                           pattern="[0-9]{5}-?[0-9]{3}" placeholder="00000-000"
                           value="<?= htmlspecialchars((string) ($usuario['cep'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="campo_formulario">
                    <label for="tipo">Tipo de usuário</label>
                    <select name="tipo" id="tipo" required>
                        <option value="cliente" <?= $usuario['tipo'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                        <option value="funcionario" <?= $usuario['tipo'] === 'funcionario' ? 'selected' : '' ?>>Funcionário</option>
                    </select>
                </div>

                <div class="campo_formulario">
                    <label for="nivel_acesso">Nível de acesso</label>
                    <select name="nivel_acesso" id="nivel_acesso" required>
                        <option value="comum" <?= $usuario['nivel_acesso'] === 'comum' ? 'selected' : '' ?>>Comum</option>
                        <option value="admin" <?= $usuario['nivel_acesso'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>

                <button type="submit" class="botao_formulario">Atualizar Usuário</button>
            </form>
        </section>
    </main>

    
    
</body>
</html>
