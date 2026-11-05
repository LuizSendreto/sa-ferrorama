<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include "../../infra/conexao.php";

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

            header("Location: visu_sensores.php");
            exit;

        } catch (Throwable $e) {

            error_log($e->getMessage());
            $erro = "Erro ao cadastrar o sensor.";

        }
    }
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

    <link
        rel="stylesheet"
        href="../../assets/style/style.css?v=cadastrosensor2"
    >

</head>

<body class="body_dashboard">

    <!-- NAVBAR -->
    <header id="header_cadastro_sensor">

        <nav class="navbar">

            <!-- LOGO -->
            <div class="marca-strain">

                <i data-lucide="train-front"></i>

                <span>STRAIN</span>

            </div>

            <!-- VOLTAR -->
            <button
                type="button"
                class="btn"
                onclick="location.href='visu_sensores.php'"
            >
                Voltar
            </button>

        </nav>

    </header>

    <!-- ÁREA PRINCIPAL -->
    <main class="area_formulario">

        <section class="painel_formulario">

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
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>
