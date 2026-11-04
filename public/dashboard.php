
<?php
require_once '../infra/verificar_acesso.php';
verificarFuncionario();

$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';
$nome = $_SESSION['usuario_nome'] ?? 'Funcionário';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['csrf_cep'])) {
    $_SESSION['csrf_cep'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="stylesheet" href="../assets/style/clima.css">
    <script src="../Scripts/clima.js"></script>
    <link rel="stylesheet" href="../assets/style/cep.css">
    <script src="../Scripts/cep.js"></script>
    
</head>

<body class="body_dashboard">

    <header id="header_dashboard">
        <nav class="navbar">

            <div class="marca-strain">
                <i data-lucide="train-front"></i>
                <span>STRAIN</span>
            </div>

            <div class="saudacao-navbar">
                <p>
                    Olá, <?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>!
                </p>
            </div>

        </nav>
    </header>

    <!-- SIDEBAR PADRONIZADA -->
    <aside id="sidebar_dashboard">

        <h2 id="titulo_sidebar">Dashboard</h2>

        <ul class="menu_sidebar">

            <li>
                <a href="dashboard.php" class="active_sidebar">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="Trens/visualizar_trens.php">
                    Trens
                </a>
            </li>

            <li>
                <a href="rotas/rotas.php">
                    Rotas
                </a>
            </li>

            <li>
                <a href="monitoramento.php">
                    Monitoramento
                </a>
            </li>

            <li>
                <a href="alertas.php">
                    Alertas
                </a>
            </li>

            <li>
                <a href="relatorios.php">
                    Relatórios
                </a>
            </li>

            <?php if ($admin) { ?>
                <li>
                    <a href="usuarios/visu_usuarios.php">
                        Visualização de Usuários
                    </a>
                </li>
            <?php } ?>

            <li>
                <a href="sensores/visu_sensores.php">
                    Visualização de Sensores
                </a>
            </li>

            <li>
                <a href="usuarios/logout.php">
                    Logout
                </a>
            </li>

        </ul>

    </aside>

    <!-- CONTEÚDO DO DASHBOARD -->
    <main id="main_dashboard">

        <div class="fotos_dashboard">
            <img
                id="fotos_dashboard"
                src="../assets/img/dashboard_certo.png"
                alt="Dashboard Strain"
            >
        </div>

        <!-- API DE CLIMA  NA TELA INDEX  -->

        <section class="secao_clima">

            <h2>Clima em Joinville</h2>

            <div class="card_clima">

                <h3 id="local_clima">📍 Joinville - SC</h3>

                <p id="temperatura_clima">Carregando...</p>

                <p id="condicao_clima">Consultando clima...</p>

                <p id="data_clima">Aguardando atualização...</p>

            </div>

        </section>

        
    <!-- FIM Clima -->
        
 
<!-- CONSULTA DE CEP -->

    <section class="secao_cep_dashboard strain-cep"
            id="strain-cep"
            data-api="api/cep_usuario.php"
            data-csrf="<?= htmlspecialchars($_SESSION['csrf_cep'], ENT_QUOTES, 'UTF-8') ?>">

        <h2>📍 Consulta de CEP</h2>
        <p>Digite um CEP para localizar e salvar seu endereço.</p>

        <form id="strain-cep-form">

            <label for="strain-cep-input">CEP:</label>

            <div class="busca_cep strain-cep-busca">

                <input
                    type="text"
                    id="strain-cep-input"
                    placeholder="00000-000"
                    maxlength="9"
                    inputmode="numeric"
                    required
                >

                <button type="submit" id="strain-cep-consultar">
                    Consultar
                </button>

            </div>

            <p id="strain-cep-mensagem"
            role="status"
            aria-live="polite"></p>

            <div class="campos_endereco strain-cep-campos">

                <div>
                    <label for="strain-cep-logradouro">
                        Logradouro:
                    </label>

                    <input
                        type="text"
                        id="strain-cep-logradouro"
                        readonly
                    >
                </div>

                <div>
                    <label for="strain-cep-bairro">
                        Bairro:
                    </label>

                    <input
                        type="text"
                        id="strain-cep-bairro"
                        readonly
                    >
                </div>

                <div>
                    <label for="strain-cep-cidade">
                        Cidade:
                    </label>

                    <input
                        type="text"
                        id="strain-cep-cidade"
                        readonly
                    >
                </div>

                <div>
                    <label for="strain-cep-uf">
                        Estado (UF):
                    </label>

                    <input
                        type="text"
                        id="strain-cep-uf"
                        maxlength="2"
                        readonly
                    >
            </div>

                </div>

            <!-- SALVAR ENDEREÇO NO BANCO -->
            <button type="button"
                    id="strain-cep-salvar"
                    disabled>
                Salvar endereço
            </button>

        </form>

    </section>

<!-- FIM CONSULTA DE CEP -->



    </main>

    
</body>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
    lucide.createIcons();
</script>
</body>
</html>
