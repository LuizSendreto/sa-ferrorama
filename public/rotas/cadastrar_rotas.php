
<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include "../../infra/conexao.php";

$erro = "";

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Valores digitados
$nome_rota = "";
$descricao = "";
$distancia = "";
$tempo_estimado = "";
$trem_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificar token CSRF
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

    $distancia_num = filter_var(
        str_replace(",", ".", $distancia),
        FILTER_VALIDATE_FLOAT
    );

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

            // Verificar se o trem selecionado existe
            $stmt = mysqli_prepare(
                $conn,
                "SELECT id FROM trens WHERE id = ?"
            );

            mysqli_stmt_bind_param($stmt, "i", $trem_int);
            mysqli_stmt_execute($stmt);

            $trem_existe = mysqli_num_rows(
                mysqli_stmt_get_result($stmt)
            ) > 0;

            mysqli_stmt_close($stmt);

            if (!$trem_existe) {
                $erro = "O trem selecionado não existe.";

            } else {

                // Cadastrar a rota
                $sql = "INSERT INTO rotas
                        (nome_rota, descricao, distancia, tempo_estimado, data_criacao, trem_id)
                        VALUES (?, ?, ?, ?, CURDATE(), ?)";

                $stmt = mysqli_prepare($conn, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssdii",
                    $nome_rota,
                    $descricao,
                    $distancia_num,
                    $tempo_int,
                    $trem_int
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                header("Location: rotas.php");
                exit;
            }

        } catch (Throwable $e) {
            error_log($e->getMessage());
            $erro = "Erro ao cadastrar a rota.";
        }
    }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Rotas ● Strain</title>

    <link rel="stylesheet"
          href="../../assets/style/style.css?v=rotas2">
</head>

<body class="body_dashboard">

    <!-- NAVBAR -->
    <header id="header_rotas">
        <nav class="navbar">

            <div class="marca-strain">
                <i data-lucide="train-front"></i>
                <span>STRAIN</span>
            </div>

            <button
                class="btn"
                type="button"
                onclick="location.href='rotas.php'"
            >
                Voltar
            </button>

        </nav>
    </header>

    <!-- FORMULÁRIO CENTRALIZADO -->
    <main class="area_formulario">

        <section class="painel_formulario">

            <h1 class="titulo_formulario">
                Cadastro de Rotas
            </h1>

            <!-- MENSAGEM DE ERRO -->
            <?php if ($erro !== "") { ?>

                <div
                    class="mensagem_erro_formulario"
                    role="alert"
                >
                    <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </div>

            <?php } ?>

            <form
                action="cadastrar_rotas.php"
                method="POST"
                class="formulario_strain"
            >

                <!-- TOKEN CSRF -->
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
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
                        placeholder="Digite o nome da rota"
                        maxlength="100"
                        value="<?= htmlspecialchars($nome_rota, ENT_QUOTES, 'UTF-8') ?>"
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
                        placeholder="Digite a descrição da rota"
                        maxlength="255"
                        rows="3"
                        required
                    ><?= htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') ?></textarea>

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
                        placeholder="Digite a distância da rota"
                        min="0.01"
                        max="9999.99"
                        step="0.01"
                        value="<?= htmlspecialchars($distancia, ENT_QUOTES, 'UTF-8') ?>"
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
                        placeholder="Digite o tempo estimado"
                        min="1"
                        step="1"
                        value="<?= htmlspecialchars($tempo_estimado, ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                </div>

                <!-- SELEÇÃO DO TREM -->
                <div class="campo_formulario">

                    <label for="trem_id">
                        Trem responsável
                    </label>

                    <select
                        name="trem_id"
                        id="trem_id"
                        required
                    >

                        <option value="">
                            Selecione o trem
                        </option>

                        <?php
                        if ($trens && mysqli_num_rows($trens) > 0) {
                            while ($trem = mysqli_fetch_assoc($trens)) {
                        ?>

                            <option
                                value="<?= (int) $trem['id'] ?>"
                                <?= (string) $trem_id === (string) $trem['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars(
                                    $trem['numero_serie'] . " - " . $trem['nome_linha'],
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

                <!-- BOTÃO CADASTRAR -->
                <button
                    type="submit"
                    id="botao_cadastro_rota"
                    class="botao_formulario"
                >
                    Cadastrar Rota
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
