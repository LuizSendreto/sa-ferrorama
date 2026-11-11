<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();
require_once __DIR__ . '/../componentes/visual.php';
require_once __DIR__ . '/../componentes/resposta_modal.php';

include "../../infra/conexao.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$erro = "";

// Criar token de segurança
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

// Manter valores após erro
$tipo_sensor = "";
$localizacao = "";
$posicao = "";

// Processar cadastro
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Verificar CSRF
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

    // Validar campos
    if (
        !array_key_exists($tipo_sensor, $tipos_validos) ||
        !array_key_exists($localizacao, $localizacoes_validas) ||
        !array_key_exists($posicao, $posicoes_validas)
    ) {
        $erro = "Selecione opções válidas para todos os campos.";

    } else {

        try {

            // Cadastrar sensor
            $sql = "INSERT INTO sensores
                    (tipo, localizacao, posicao)
                    VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $tipo_sensor,
                $localizacao,
                $posicao
            );

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            strainRespostaModal(true, "Operação concluída.");

            header("Location: visu_sensores.php");
            exit;

        } catch (Throwable $e) {

            error_log($e->getMessage());
            $erro = "Erro ao cadastrar o sensor.";

        }
    }
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

    <title>Cadastrar Sensor ● Strain</title>

    

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

            <!-- TÍTULO -->
            <h1 class="titulo_formulario">
                Cadastro de Sensor
            </h1>

            <p class="subtitulo_formulario">
                Adicione um novo sensor ao sistema Strain
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
                action="cadastro_sensor.php"
                method="POST"
                class="formulario_strain"
            >

                <!-- TOKEN CSRF -->
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
                                value="<?= htmlspecialchars(
                                    $valor,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                <?= $tipo_sensor === $valor ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars(
                                    $texto,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
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
                                value="<?= htmlspecialchars(
                                    $valor,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                <?= $localizacao === $valor ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars(
                                    $texto,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
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
                                value="<?= htmlspecialchars(
                                    $valor,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                <?= $posicao === $valor ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars(
                                    $texto,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- BOTÃO CADASTRAR -->
                <button
                    type="submit"
                    class="botao_formulario"
                >
                    Cadastrar Sensor
                </button>

            </form>

        </section>

    </main>

    <!-- ÍCONES LUCIDE -->
    

    

</body>

</html>
