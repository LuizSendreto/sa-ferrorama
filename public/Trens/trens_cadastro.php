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
$nome_linha = "";
$numero_serie = "";
$velocidade = "";
$estado = "";
$funcionario_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verificar o token CSRF
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

    $estados_validos = ["Ativo", "Inativo", "Manutencao"];

    if ($nome_linha === "" || $numero_serie === "" || $velocidade === "") {
        $erro = "Preencha todos os campos.";

    } elseif (!ctype_digit($velocidade)) {
        $erro = "A velocidade deve ser um número inteiro maior ou igual a zero.";

    } elseif (!in_array($estado, $estados_validos, true)) {
        $erro = "Status inválido.";

    } elseif (!ctype_digit((string) $funcionario_id) || (int) $funcionario_id <= 0) {
        $erro = "Selecione um funcionário.";

    } else {

        $velocidade_int = (int) $velocidade;
        $funcionario_int = (int) $funcionario_id;

        try {

            // Verificar se o responsável existe e é funcionário
            $stmt = mysqli_prepare(
                $conn,
                "SELECT id FROM usuarios
                 WHERE id = ? AND tipo = 'funcionario'"
            );

            mysqli_stmt_bind_param($stmt, "i", $funcionario_int);
            mysqli_stmt_execute($stmt);

            $funcionario_existe = mysqli_num_rows(
                mysqli_stmt_get_result($stmt)
            ) > 0;

            mysqli_stmt_close($stmt);

            if (!$funcionario_existe) {
                $erro = "Funcionário selecionado é inválido.";

            } else {

                // Não permitir números de série duplicados
                $stmt = mysqli_prepare(
                    $conn,
                    "SELECT id FROM trens WHERE numero_serie = ?"
                );

                mysqli_stmt_bind_param($stmt, "s", $numero_serie);
                mysqli_stmt_execute($stmt);

                $ja_existe = mysqli_num_rows(
                    mysqli_stmt_get_result($stmt)
                ) > 0;

                mysqli_stmt_close($stmt);

                if ($ja_existe) {
                    $erro = "Já existe um trem cadastrado com esse número de série.";

                } else {

                    // Cadastrar o trem
                    $sql = "INSERT INTO trens
                            (nome_linha, numero_serie, velocidade, estado, funcionario_id)
                            VALUES (?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($conn, $sql);

                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssisi",
                        $nome_linha,
                        $numero_serie,
                        $velocidade_int,
                        $estado,
                        $funcionario_int
                    );

                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    header("Location: visualizar_trens.php");
                    exit;
                }
            }

        } catch (Throwable $e) {
            error_log($e->getMessage());
            $erro = "Erro ao cadastrar o trem.";
        }
    }
}

// Buscar somente funcionários da nova tabela
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trens</title>

    <link rel="stylesheet" href="../../assets/style/style.css">
</head>

<body class="body_dashboard">
    <header id="header_trens">
        <nav class="navbar">

            <div>
                <button class="btn" type="button"
                        onclick="location.href='visualizar_trens.php'">
                    Voltar
                </button>
            </div>

            <div class="logo-navbar">
                <img src="../../assets/img/icone_da_navbar.png" alt="logo usuario logado">
            </div>

        </nav>
    </header>

    <main id="main_trens">
        <h2>Cadastro de Trens</h2>

        <?php if ($erro != "") { ?>
            <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
        <?php } ?>

        <form action="trens_cadastro.php" method="POST">

            <input type="hidden" name="csrf_token"
                   value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <div>
                <label for="nome_linha">Nome da linha:</label>
                <input
                    type="text"
                    id="nome_linha"
                    name="nome_linha"
                    placeholder="Digite o nome da linha do trem"
                    value="<?php echo htmlspecialchars($nome_linha); ?>"
                    required
                >
            </div>

            <div>
                <label for="numero_serie">Número de série:</label>
                <input
                    type="text"
                    id="numero_serie"
                    name="numero_serie"
                    placeholder="Digite o número de série do trem"
                    value="<?php echo htmlspecialchars($numero_serie); ?>"
                    required
                >
            </div>

            <div>
                <label for="velocidade">Velocidade (km/h):</label>
                <input
                    type="number"
                    id="velocidade"
                    name="velocidade"
                    placeholder="Digite a velocidade do trem"
                    min="0"
                    step="1"
                    value="<?php echo htmlspecialchars($velocidade); ?>"
                    required
                >
            </div>

            <div>
                <label for="estado">Status:</label>

                <select name="estado" id="estado" required>
                    <option value="">Selecione o status:</option>
                    <option value="Ativo" <?php if ($estado == "Ativo") echo "selected"; ?>>Ativo</option>
                    <option value="Inativo" <?php if ($estado == "Inativo") echo "selected"; ?>>Inativo</option>
                    <option value="Manutencao" <?php if ($estado == "Manutencao") echo "selected"; ?>>Manutenção</option>
                </select>
            </div>

            <div>
                <label for="funcionario_id">Funcionário responsável:</label>

                <select name="funcionario_id" id="funcionario_id" required>
                    <option value="">Selecione o funcionário:</option>

                    <?php
                    if ($funcionarios && mysqli_num_rows($funcionarios) > 0) {
                        while ($funcionario = mysqli_fetch_assoc($funcionarios)) {
                    ?>
                            <option value="<?php echo (int) $funcionario['id']; ?>"
                                <?php if ((string) $funcionario_id === (string) $funcionario['id']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($funcionario['nome'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <button
                id="botao_cadastro_trem"
                type="submit"
                class="btn btn-outline-danger">
                Cadastrar Trem
            </button>

        </form>
    </main>
</body>

</html>