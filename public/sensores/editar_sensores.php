<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();
require_once __DIR__ . '/../componentes/visual.php';
require_once __DIR__ . '/../componentes/resposta_modal.php';

include "../../infra/conexao.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$erro = "";

// Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Opções permitidas
$tipos_validos = [
    "Temperatura" => "Temperatura",
    "Vibracao" => "Vibração",
    "Pressao" => "Pressão",
    "Umidade" => "Umidade"
];

$localizacoes_validas = [
    "Locomotiva" => "Locomotiva",
    "Vagao 1" => "Vagão 1",
    "Vagao 2" => "Vagão 2",
    "Vagao 3" => "Vagão 3"
];

$posicoes_validas = [
    "Ativo" => "Ativo",
    "Inativo" => "Inativo",
    "Manutencao" => "Manutenção"
];

// Receber ID do sensor
$id = filter_var(
    $_SERVER["REQUEST_METHOD"] === "POST"
        ? ($_POST["id"] ?? null)
        : ($_GET["id"] ?? null),
    FILTER_VALIDATE_INT
);

if (!$id || $id <= 0) {
    strainRespostaModal(false, "Registro inválido.", 400);
    header("Location: visu_sensores.php");
    exit;
}

// Buscar sensor
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, tipo, localizacao, posicao
     FROM sensores
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$sensor = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

mysqli_stmt_close($stmt);

if (!$sensor) {
    http_response_code(404);
    strainRespostaModal(false, "Sensor não encontrado.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Sensor não encontrado.");
}

// Processar atualização
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validar CSRF
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

    $tipo_sensor = trim($_POST["tipo_sensor"] ?? "");
    $localizacao = trim($_POST["localizacao"] ?? "");
    $posicao = trim($_POST["posicao"] ?? "");

    // Manter valores digitados se houver erro
    $sensor["tipo"] = $tipo_sensor;
    $sensor["localizacao"] = $localizacao;
    $sensor["posicao"] = $posicao;

    // Validar opções
    if (
        !array_key_exists($tipo_sensor, $tipos_validos) ||
        !array_key_exists($localizacao, $localizacoes_validas) ||
        !array_key_exists($posicao, $posicoes_validas)
    ) {
        $erro = "Selecione opções válidas para todos os campos.";

    } else {

        try {

            $sql = "UPDATE sensores
                    SET tipo = ?,
                        localizacao = ?,
                        posicao = ?
                    WHERE id = ?";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sssi",
                $tipo_sensor,
                $localizacao,
                $posicao,
                $id
            );

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            strainRespostaModal(true, "Operação concluída.");

            header("Location: visu_sensores.php");
            exit;

        } catch (Throwable $e) {

            error_log($e->getMessage());
            $erro = "Erro ao atualizar o sensor.";

        }
    }
}


function normalizarSensor($valor) {
    $valor = mb_strtolower(trim((string) $valor), 'UTF-8');

    return strtr($valor, [
        'á' => 'a',
        'à' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'é' => 'e',
        'ê' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ú' => 'u',
        'ç' => 'c'
    ]);
}

// Retornar a opção equivalente ao valor do banco
function selecionarValorSensor($valorBanco, $opcoes) {
    foreach ($opcoes as $valor => $texto) {
        if (
            normalizarSensor($valorBanco) === normalizarSensor($valor) ||
            normalizarSensor($valorBanco) === normalizarSensor($texto)
        ) {
            return $valor;
        }
    }

    return null;
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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar Sensor ● Strain</title>

    
<link rel="stylesheet" href="../../assets/style/listagens.css?v=4">
<link rel="stylesheet" href="../../assets/style/admin_paginas.css?v=4">
<script src="../../Scripts/listagens.js?v=2" defer></script>
</head>

<body class="strain-listagem strain-admin">

    <!-- NAVBAR -->
    <?php strainNavegacao('Sensores', '../', $_SESSION['usuario_nome'] ?? 'Funcionário', ($_SESSION['nivel_acesso'] ?? '') === 'admin'); ?>

    <!-- ÁREA PRINCIPAL -->
    <main class="strain-conteudo">

        <section class="painel_formulario">
<a class="strain-btn strain-neutro" style="margin-bottom:20px" href="visu_sensores.php">Voltar para sensores</a>

            <h1 class="titulo_formulario">
                Editar Sensor
            </h1>

            <p class="subtitulo_formulario">
                Editando o sensor Nº <?= (int) $sensor['id'] ?>
            </p>

            <!-- ERROS -->
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
                action="editar_sensores.php"
                method="POST"
                class="formulario_strain"
            >

                <!-- ID -->
                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $sensor['id'] ?>"
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

                <!-- TIPO DE SENSOR -->
                <div class="campo_formulario">

                    <label for="tipo_sensor">
                        Tipo de Sensor
                    </label>

                    <select
                        name="tipo_sensor"
                        id="tipo_sensor"
                        required
                    >

                        <option value="">
                            Selecione o tipo
                        </option>

                        <?php foreach ($tipos_validos as $valor => $texto) { ?>

                            <option
                                value="<?= htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') ?>"
                                <?= selecionarValorSensor($sensor['tipo'], $tipos_validos) === $valor ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- LOCALIZAÇÃO -->
                <div class="campo_formulario">

                    <label for="localizacao">
                        Localização
                    </label>

                    <select
                        name="localizacao"
                        id="localizacao"
                        required
                    >

                        <option value="">
                            Selecione a localização
                        </option>

                        <?php foreach ($localizacoes_validas as $valor => $texto) { ?>

                            <option
                                value="<?= htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') ?>"
                                <?= selecionarValorSensor($sensor['localizacao'], $localizacoes_validas) === $valor ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- STATUS -->
                <div class="campo_formulario">

                    <label for="posicao">
                        Status
                    </label>

                    <select
                        name="posicao"
                        id="posicao"
                        required
                    >

                        <option value="">
                            Selecione o status
                        </option>

                        <?php foreach ($posicoes_validas as $valor => $texto) { ?>

                            <option
                                value="<?= htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') ?>"
                                <?= selecionarValorSensor($sensor['posicao'], $posicoes_validas) === $valor ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- ATUALIZAR -->
                <button
                    type="submit"
                    class="botao_formulario"
                >
                    Atualizar Sensor
                </button>

            </form>

        </section>

    </main>

    <!-- LUCIDE -->
    

    

</body>
</html>
