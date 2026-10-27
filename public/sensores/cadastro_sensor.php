<?php

require_once '../../infra/verificar_acesso.php';
verificarAdmin();

include "../../infra/conexao.php";

$erro = "";

// Criar token de segurança
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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

    $tipo_sensor = trim($_POST["tipo_sensor"] ?? "");
    $localizacao = trim($_POST["localizacao"] ?? "");
    $posicao = trim($_POST["posicao"] ?? "");

    // Valores permitidos
    $tipos_validos = ["Temperatura", "Vibracao", "Pressao", "Umidade"];
    $localizacoes_validas = ["Locomotiva", "Vagao 1", "Vagao 2", "Vagao 3"];
    $posicoes_validas = ["Ativo", "Inativo", "Manutencao"];

    // Validar os campos
    if (
        !in_array($tipo_sensor, $tipos_validos, true) ||
        !in_array($localizacao, $localizacoes_validas, true) ||
        !in_array($posicao, $posicoes_validas, true)
    ) {
        $erro = "Selecione opções válidas para todos os campos.";

    } else {

        $sql = "INSERT INTO sensores (tipo, localizacao, posicao)
                VALUES (?, ?, ?)";

        try {
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

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Sensor</title>

    <link rel="stylesheet" href="../../assets/style/style.css">

</head>

<body class="body_dashboard">
    <header id="header_cadastro_sensor">
        <nav class="navbar">
            <div class="logo-navbar">
                <img src="../../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>
        </nav>
    </header>

    <main id="main_cadastro_sensor">
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
                <li><a href="../../index.php">Início</a></li>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="../Trens/visualizar_trens.php">Trens</a></li>
                <li><a href="../rotas/rotas.php">Rotas</a></li>
                <li><a href="../monitoramento.php">Monitoramento</a></li>
                <li><a href="../alertas.php">Alertas</a></li>
                <li><a href="../relatorios.php">Relatórios</a></li>
                <li><a href="../usuarios/visu_usuarios.php">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php" class="active_sidebar">Visualização de Sensores</a></li>
                <li><a href="../usuarios/logout.php">Logout</a></li>
            </ul>
        </aside>

        <div>
            <h2>Adicione um novo sensor!</h2>
            <div id="conteudo_cadastro_sensor">

                <h1>Cadastro de Sensor</h1>

                <?php if ($erro != "") { ?>
                    <p style="color:red;">
                        <?php echo htmlspecialchars($erro); ?>
                    </p>
                <?php } ?>

                <form id="form_cadastro_sensor" action="cadastro_sensor.php" method="POST">

                    <input type="hidden" name="csrf_token"
                           value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                    <label for="tipo_sensor">Tipo de Sensor:</label>

                    <select name="tipo_sensor" id="tipo_sensor" required>
                        <option value="">Selecione o tipo</option>
                        <option value="Temperatura">Temperatura</option>
                        <option value="Vibracao">Vibração</option>
                        <option value="Pressao">Pressão</option>
                        <option value="Umidade">Umidade</option>
                    </select>

                    <label for="localizacao">Localização:</label>

                    <select name="localizacao" id="localizacao" required>
                        <option value="">Selecione a localização</option>
                        <option value="Locomotiva">Locomotiva</option>
                        <option value="Vagao 1">Vagão 1</option>
                        <option value="Vagao 2">Vagão 2</option>
                        <option value="Vagao 3">Vagão 3</option>
                    </select>

                    <label for="posicao">Status:</label>

                    <select name="posicao" id="posicao" required>
                        <option value="">Selecione o status</option>
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                        <option value="Manutencao">Manutenção</option>
                    </select>

                    <button type="submit">
                        Cadastrar Sensor
                    </button>

                </form>

            </div>
        </div>

    </main>

    <footer></footer>

</body>

</html>