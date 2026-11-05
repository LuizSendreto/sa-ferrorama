
<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include "../../infra/conexao.php";

$erro = "";
$enviado = null;

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Linhas disponíveis
$linhas_validas = [
    "Linha Azul (Sul)",
    "Linha Verde (Centro)",
    "Linha Amarela (Itaum)",
    "Linha Vermelha (Villa Nova)"
];

// Receber ID do trem
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int) ($_POST["id"] ?? 0);
} else {
    $id = (int) ($_GET["id"] ?? 0);
}

// Verificar ID
if ($id <= 0) {
    header("Location: visualizar_trens.php");
    exit;
}

// Buscar o trem no banco
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM trens WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$trem = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

mysqli_stmt_close($stmt);

if (!$trem) {
    http_response_code(404);
    exit("Trem não encontrado.");
}

$titulo_trem = $trem["nome_linha"];

// Processar edição
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Verificar token CSRF
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        exit("Solicitação inválida.");
    }

    $nome_linha = trim($_POST["nome_linha"] ?? "");
    $numero_serie = trim($_POST["numero_serie"] ?? "");
    $velocidade = trim($_POST["velocidade"] ?? "");
    $estado = $_POST["estado"] ?? "";
    $funcionario_id = $_POST["funcionario_id"] ?? "";

    // Manter dados enviados caso ocorra erro
    $enviado = [
        "nome_linha" => $nome_linha,
        "numero_serie" => $numero_serie,
        "velocidade" => $velocidade,
        "estado" => $estado,
        "funcionario_id" => $funcionario_id
    ];

    $estados_validos = [
        "Ativo",
        "Inativo",
        "Manutencao"
    ];

    // Validação
    if (
        $nome_linha === "" ||
        $numero_serie === "" ||
        $velocidade === ""
    ) {
        $erro = "Preencha todos os campos.";

    } elseif (!in_array($nome_linha, $linhas_validas, true)) {
        $erro = "Selecione uma linha válida.";

    } elseif (!ctype_digit($velocidade)) {
        $erro = "A velocidade deve ser um número inteiro maior ou igual a zero.";

    } elseif (!in_array($estado, $estados_validos, true)) {
        $erro = "Status inválido.";

    } elseif (
        !ctype_digit((string) $funcionario_id) ||
        (int) $funcionario_id <= 0
    ) {
        $erro = "Selecione um funcionário.";

    } else {

        $velocidade_int = (int) $velocidade;
        $funcionario_int = (int) $funcionario_id;

        try {

            // Verificar funcionário responsável
            $stmt = mysqli_prepare(
                $conn,
                "SELECT id FROM usuarios
                 WHERE id = ? AND tipo = 'funcionario'"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $funcionario_int
            );

            mysqli_stmt_execute($stmt);

            $funcionario_existe = mysqli_num_rows(
                mysqli_stmt_get_result($stmt)
            ) > 0;

            mysqli_stmt_close($stmt);

            if (!$funcionario_existe) {

                $erro = "O funcionário selecionado é inválido.";

            } else {

                // Verificar número de série duplicado
                $stmt = mysqli_prepare(
                    $conn,
                    "SELECT id FROM trens
                     WHERE numero_serie = ? AND id <> ?"
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    "si",
                    $numero_serie,
                    $id
                );

                mysqli_stmt_execute($stmt);

                $ja_existe = mysqli_num_rows(
                    mysqli_stmt_get_result($stmt)
                ) > 0;

                mysqli_stmt_close($stmt);

                if ($ja_existe) {

                    $erro = "Já existe outro trem cadastrado com esse número de série.";

                } else {

                    // Atualizar trem
                    $sql = "UPDATE trens
                            SET nome_linha = ?,
                                numero_serie = ?,
                                velocidade = ?,
                                estado = ?,
                                funcionario_id = ?
                            WHERE id = ?";

                    $stmt = mysqli_prepare($conn, $sql);

                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssisii",
                        $nome_linha,
                        $numero_serie,
                        $velocidade_int,
                        $estado,
                        $funcionario_int,
                        $id
                    );

                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    header("Location: visualizar_trens.php");
                    exit;
                }
            }

        } catch (Throwable $e) {

            error_log($e->getMessage());
            $erro = "Erro ao atualizar o trem.";

        }
    }
}

// Manter os dados digitados se houver erro
if ($enviado !== null) {
    $trem = array_merge($trem, $enviado);
}

// Buscar funcionários
$funcionarios = mysqli_query(
    $conn,
    "SELECT id, nome
     FROM usuarios
     WHERE tipo = 'funcionario'
     ORDER BY nome ASC"
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

    <title>Editar Trem ● Strain</title>

    <link
        rel="stylesheet"
        href="../../assets/style/style.css?v=editartrens1"
    >
</head>

<body class="body_dashboard">

    <!-- NAVBAR -->
    <header id="header_trens">

        <nav class="navbar">

            <!-- LOGO -->
            <div class="marca-strain">

                <i data-lucide="train-front"></i>
                <span>STRAIN</span>

            </div>

            <!-- BOTÃO VOLTAR -->
            <button
                type="button"
                class="btn"
                onclick="location.href='visualizar_trens.php'"
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
                Editar Trem
            </h1>

            <p class="subtitulo_formulario">
                Editando o trem:
                <?= htmlspecialchars(
                    $titulo_trem,
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
                action="editar_trens.php"
                method="POST"
                class="formulario_strain"
            >

                <!-- ID -->
                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $id ?>"
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

                <!-- NOME DA LINHA -->
                <div class="campo_formulario">

                    <label for="nome_linha">
                        Nome da linha
                    </label>

                    <select
                        id="nome_linha"
                        name="nome_linha"
                        required
                    >

                        <option value="">
                            Selecione a linha do trem
                        </option>

                        <?php foreach ($linhas_validas as $linha) { ?>

                            <option
                                value="<?= htmlspecialchars($linha, ENT_QUOTES, 'UTF-8') ?>"
                                <?= $trem['nome_linha'] === $linha ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($linha, ENT_QUOTES, 'UTF-8') ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- NÚMERO DE SÉRIE -->
                <div class="campo_formulario">

                    <label for="numero_serie">
                        Número de série
                    </label>

                    <input
                        type="text"
                        id="numero_serie"
                        name="numero_serie"
                        placeholder="Digite o número de série"
                        value="<?= htmlspecialchars(
                            $trem['numero_serie'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >

                </div>

                <!-- VELOCIDADE -->
                <div class="campo_formulario">

                    <label for="velocidade">
                        Velocidade (km/h)
                    </label>

                    <input
                        type="number"
                        id="velocidade"
                        name="velocidade"
                        min="0"
                        step="1"
                        placeholder="Digite a velocidade do trem"
                        value="<?= htmlspecialchars(
                            (string) $trem['velocidade'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >

                </div>

                <!-- STATUS -->
                <div class="campo_formulario">

                    <label for="estado">
                        Status
                    </label>

                    <select
                        name="estado"
                        id="estado"
                        required
                    >

                        <option value="">
                            Selecione o status
                        </option>

                        <option
                            value="Ativo"
                            <?= $trem['estado'] === 'Ativo' ? 'selected' : '' ?>
                        >
                            Ativo
                        </option>

                        <option
                            value="Inativo"
                            <?= $trem['estado'] === 'Inativo' ? 'selected' : '' ?>
                        >
                            Inativo
                        </option>

                        <option
                            value="Manutencao"
                            <?= $trem['estado'] === 'Manutencao' ? 'selected' : '' ?>
                        >
                            Manutenção
                        </option>

                    </select>

                </div>

                <!-- FUNCIONÁRIO RESPONSÁVEL -->
                <div class="campo_formulario">

                    <label for="funcionario_id">
                        Funcionário responsável
                    </label>

                    <select
                        name="funcionario_id"
                        id="funcionario_id"
                        required
                    >

                        <option value="">
                            Selecione o funcionário
                        </option>

                        <?php
                        if ($funcionarios) {
                            while ($f = mysqli_fetch_assoc($funcionarios)) {
                        ?>

                            <option
                                value="<?= (int) $f['id'] ?>"
                                <?= (string) $f['id'] ===
                                    (string) $trem['funcionario_id']
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= htmlspecialchars(
                                    $f['nome'],
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
                    Atualizar Trem
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
