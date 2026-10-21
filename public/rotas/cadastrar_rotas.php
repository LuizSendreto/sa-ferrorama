<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

include "../../infra/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome_rota      = trim($_POST["nome_rota"]);
    $descricao      = trim($_POST["descricao"]);
    $distancia      = (float) str_replace(",", ".", $_POST["distancia"]);
    $tempo_estimado = (int) $_POST["tempo_estimado"];
    $trem_id        = (int) $_POST["trem_id"];

    if ($nome_rota === "" || $descricao === "") {
        $erro = "Preencha o nome e a descrição da rota.";
    } elseif ($distancia <= 0 || $distancia > 9999.99) {
        $erro = "Informe uma distância entre 0,01 e 9999,99 km.";
    } elseif ($tempo_estimado <= 0) {
        $erro = "O tempo estimado deve ser maior que zero.";
    } elseif ($trem_id <= 0) {
        $erro = "Selecione um trem.";
    } else {
        $sql = "INSERT INTO rotas
                (nome_rota, descricao, distancia, tempo_estimado, data_criacao, trem_id)
                VALUES (?, ?, ?, ?, CURDATE(), ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssdii",
            $nome_rota,
            $descricao,
            $distancia,
            $tempo_estimado,
            $trem_id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: rotas.php");
            exit();
        } else {
            $erro = "Erro ao cadastrar rota: " . mysqli_stmt_error($stmt);
        }
    }
}

$trens = mysqli_query($conn, "SELECT id, nome_linha FROM trens ORDER BY nome_linha ASC");

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

            <div>
                <label for="nome_rota">Nome da rota:</label>
                <input
                    type="text"
                    id="nome_rota"
                    name="nome_rota"
                    placeholder="Digite o nome da rota"
                    required
                >
            </div>

            <div>
                <label for="descricao">Descrição:</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    placeholder="Digite a descrição da rota"
                    required
                ></textarea>
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
                            <option value="<?php echo (int) $trem['id']; ?>">
                                <?php echo htmlspecialchars($trem['nome_linha'], ENT_QUOTES, 'UTF-8'); ?>
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
                class="btn btn-outline-danger"
            >Cadastrar Rota</button>

        </form>
    </main>
</body>

</html>