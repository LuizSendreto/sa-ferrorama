
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

    $estados_validos = ["Ativo", "Inativo", "Manutencao"];

    if ($nome_rota === "" || $descricao === "") {
        $erro = "Preencha o nome e a descrição da rota.";

    } elseif (mb_strlen($nome_rota) > 100) {
        $erro = "O nome da rota pode ter no máximo 100 caracteres.";

    } elseif (mb_strlen($descricao) > 255) {
        $erro = "A descrição pode ter no máximo 255 caracteres.";

    } elseif ($distancia_num === false || $distancia_num < 0.01 || $distancia_num > 9999.99) {
        $erro = "Informe uma distância entre 0,01 e 9999,99 km.";

    } elseif (!ctype_digit((string) $tempo_estimado) || (int) $tempo_estimado <= 0) {
        $erro = "O tempo estimado deve ser um número inteiro maior que zero.";

    } elseif (!ctype_digit((string) $trem_id) || (int) $trem_id <= 0) {
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
    <title>Cadastro de Rotas</title>

    <link rel="stylesheet" href="../../assets/style/style.css">
</head>

<body class="body_dashboard">
    <header id="header_trens">
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

            <div class="logo-navbar">
                <img src="../../assets/img/icone_da_navbar.png" alt="logo usuario logado">
            </div>

        </nav>
    </header>

    <main id="main_trens">
        <h2>Cadastro de Rotas</h2>

        <?php if ($erro != "") { ?>
            <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
        <?php } ?>

        <form action="cadastrar_rotas.php" method="POST">

            <input type="hidden" name="csrf_token"
                   value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <div>
                <label for="nome_rota">Nome da rota:</label>
                <input
                    type="text"
                    id="nome_rota"
                    name="nome_rota"
                    placeholder="Digite o nome da rota"
                    maxlength="100"
                    value="<?php echo htmlspecialchars($nome_rota); ?>"
                    required
                >
            </div>

            <div>
                <label for="descricao">Descrição:</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    placeholder="Digite a descrição da rota"
                    maxlength="255"
                    required
                ><?php echo htmlspecialchars($descricao); ?></textarea>
            </div>

            <div>
                <label for="distancia">Distância (km):</label>
                <input
                    type="number"
                    id="distancia"
                    name="distancia"
                    placeholder="Digite a distância da rota"
                    min="0.01"
                    max="9999.99"
                    step="0.01"
                    value="<?php echo htmlspecialchars($distancia); ?>"
                    required
                >
            </div>

            <div>
                <label for="tempo_estimado">Tempo estimado (minutos):</label>
                <input
                    type="number"
                    id="tempo_estimado"
                    name="tempo_estimado"
                    placeholder="Digite o tempo estimado"
                    min="1"
                    step="1"
                    value="<?php echo htmlspecialchars($tempo_estimado); ?>"
                    required
                >
            </div>

            <div>
                <label for="trem_id">Trem:</label>
                <select name="trem_id" id="trem_id" required>
                    <option value="">Selecione o trem</option>

                    <?php
                    if ($trens && mysqli_num_rows($trens) > 0) {
                        while ($trem = mysqli_fetch_assoc($trens)) {
                    ?>
                            <option value="<?php echo (int) $trem['id']; ?>"
                                <?php if ((string) $trem_id === (string) $trem['id']) echo "selected"; ?>>
                                <?php echo htmlspecialchars($trem['numero_serie'] . " - " . $trem['nome_linha'], ENT_QUOTES, 'UTF-8'); ?>
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
                Cadastrar Rota
            </button>

        </form>
    </main>
</body>

</html>
