<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();
require_once __DIR__ . '/../componentes/visual.php';

include "../../infra/conexao.php";

$erro = "";
$enviado = null;

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Receber o ID da rota
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int) ($_POST["id"] ?? 0);
} else {
    $id = (int) ($_GET["id"] ?? 0);
}

// Verificar ID
if ($id <= 0) {
    header("Location: rotas.php");
    exit;
}

// Buscar a rota no banco
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM rotas WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$rota = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

mysqli_stmt_close($stmt);

if (!$rota) {
    http_response_code(404);
    exit("Rota não encontrada.");
}

$titulo_rota = $rota["nome_rota"];

// Processar a edição
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Verificar CSRF
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        exit("Solicitação inválida.");
    }

    $nome_rota = trim($_POST["nome_rota"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $distancia = trim($_POST["distancia"] ?? "");
    $tempo_estimado = trim($_POST["tempo_estimado"] ?? "");
    $trem_id = $_POST["trem_id"] ?? "";

    // Guardar dados digitados
    $enviado = [
        "nome_rota" => $nome_rota,
        "descricao" => $descricao,
        "distancia" => $distancia,
        "tempo_estimado" => $tempo_estimado,
        "trem_id" => $trem_id
    ];

    $distancia_num = filter_var(
        str_replace(",", ".", $distancia),
        FILTER_VALIDATE_FLOAT
    );

    // Validação
    if ($nome_rota === "" || $descricao === "") {
        $erro = "Preencha o nome e a descrição da rota.";

    } elseif (mb_strlen($nome_rota) > 100) {
        $erro = "O nome da rota pode ter no máximo 100 caracteres.";

    } elseif (mb_strlen($descricao) > 255) {
        $erro = "A descrição pode ter no máximo 255 caracteres.";

    } elseif (
        $distancia_num === false ||
        $distancia_num < 0.01 ||
        $distancia_num > 9999.99
    ) {
        $erro = "Informe uma distância entre 0,01 e 9999,99 km.";

    } elseif (
        !ctype_digit((string) $tempo_estimado) ||
        (int) $tempo_estimado <= 0
    ) {
        $erro = "O tempo estimado deve ser um número inteiro maior que zero.";

    } elseif (
        !ctype_digit((string) $trem_id) ||
        (int) $trem_id <= 0
    ) {
        $erro = "Selecione um trem.";

    } else {

        $tempo_int = (int) $tempo_estimado;
        $trem_int = (int) $trem_id;

        try {

            // Verificar se o trem existe
            $stmt = mysqli_prepare(
                $conn,
                "SELECT id FROM trens WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $trem_int
            );

            mysqli_stmt_execute($stmt);

            $trem_existe = mysqli_num_rows(
                mysqli_stmt_get_result($stmt)
            ) > 0;

            mysqli_stmt_close($stmt);

            if (!$trem_existe) {

                $erro = "O trem selecionado não existe.";

            } else {

                // Atualizar rota
                $sql = "UPDATE rotas
                        SET nome_rota = ?,
                            descricao = ?,
                            distancia = ?,
                            tempo_estimado = ?,
                            trem_id = ?
                        WHERE id = ?";

                $stmt = mysqli_prepare($conn, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssdiii",
                    $nome_rota,
                    $descricao,
                    $distancia_num,
                    $tempo_int,
                    $trem_int,
                    $id
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                header("Location: rotas.php");
                exit;
            }

        } catch (Throwable $e) {

            error_log($e->getMessage());
            $erro = "Erro ao atualizar a rota.";

        }
    }
}

// Manter os dados digitados em caso de erro
if ($enviado !== null) {
    $rota = array_merge($rota, $enviado);
}

// Buscar trens disponíveis
$trens = mysqli_query(
    $conn,
    "SELECT id, nome_linha, numero_serie
     FROM trens
     ORDER BY nome_linha ASC"
);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar Rota ● Strain</title>

    

<link rel="stylesheet" href="../../assets/style/listagens.css?v=4">
<link rel="stylesheet" href="../../assets/style/admin_paginas.css?v=4">
<script src="../../Scripts/listagens.js?v=2" defer></script>
</head>

<body class="strain-listagem strain-admin">

    <!-- NAVBAR -->
    <?php strainNavegacao('Rotas', '../', $_SESSION['usuario_nome'] ?? 'Funcionário', ($_SESSION['nivel_acesso'] ?? '') === 'admin'); ?>

    <!-- ÁREA DO FORMULÁRIO -->
    <main class="strain-conteudo">

        <section class="painel_formulario">
<a class="strain-btn strain-neutro" style="margin-bottom:20px" href="rotas.php">Voltar para rotas</a>

            <!-- TÍTULO -->
            <h1 class="titulo_formulario">
                Editar Rota
            </h1>

            <p class="subtitulo_formulario">

                Editando a rota:
                <?= htmlspecialchars(
                    $titulo_rota,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </p>

            <!-- MENSAGEM DE ERRO -->
            <?php if ($erro !== "") { ?>

                <div
                    class="mensagem_erro_formulario"
                    role="alert"
                >

                    <?= htmlspecialchars(
                        $erro,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            <?php } ?>

            <!-- FORMULÁRIO -->
            <form
                action="editar_rotas.php"
                method="POST"
                class="formulario_strain"
            >

                <!-- ID -->
                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $id ?>"
                >

                <!-- CSRF -->
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        $_SESSION['csrf_token'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <!-- NOME DA ROTA -->
                <div class="campo_formulario">

                    <label for="nome_rota">
                        Nome da rota
                    </label>

                    <input
                        type="text"
                        id="nome_rota"
                        name="nome_rota"
                        maxlength="100"
                        placeholder="Digite o nome da rota"
                        value="<?= htmlspecialchars(
                            $rota['nome_rota'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >

                </div>

                <!-- DESCRIÇÃO -->
                <div class="campo_formulario">

                    <label for="descricao">
                        Descrição
                    </label>

                    <textarea
                        id="descricao"
                        name="descricao"
                        maxlength="255"
                        rows="3"
                        placeholder="Digite a descrição da rota"
                        required
                    ><?= htmlspecialchars(
                        $rota['descricao'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?></textarea>

                </div>

                <!-- DISTÂNCIA -->
                <div class="campo_formulario">

                    <label for="distancia">
                        Distância (km)
                    </label>

                    <input
                        type="number"
                        id="distancia"
                        name="distancia"
                        min="0.01"
                        max="9999.99"
                        step="0.01"
                        placeholder="Digite a distância"
                        value="<?= htmlspecialchars(
                            (string) $rota['distancia'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >

                </div>

                <!-- TEMPO ESTIMADO -->
                <div class="campo_formulario">

                    <label for="tempo_estimado">
                        Tempo estimado (minutos)
                    </label>

                    <input
                        type="number"
                        id="tempo_estimado"
                        name="tempo_estimado"
                        min="1"
                        step="1"
                        placeholder="Digite o tempo estimado"
                        value="<?= htmlspecialchars(
                            (string) $rota['tempo_estimado'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >

                </div>

                <!-- TREM VINCULADO -->
                <div class="campo_formulario">

                    <label for="trem_id">
                        Trem vinculado
                    </label>

                    <select
                        id="trem_id"
                        name="trem_id"
                        required
                    >

                        <option value="">
                            Selecione o trem
                        </option>

                        <?php
                        if ($trens) {
                            while (
                                $t = mysqli_fetch_assoc($trens)
                            ) {
                        ?>

                            <option
                                value="<?= (int) $t['id'] ?>"
                                <?= (string) $t['id'] ===
                                    (string) $rota['trem_id']
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= htmlspecialchars(
                                    $t['numero_serie'] .
                                    ' - ' .
                                    $t['nome_linha'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </option>

                        <?php
                            }
                        }
                        ?>

                    </select>

                </div>

                <!-- BOTÃO ATUALIZAR -->
                <button
                    type="submit"
                    class="botao_formulario"
                >
                    Atualizar Rota
                </button>

            </form>

        </section>

    </main>

    <!-- ÍCONES -->
    

    

</body>

</html>
