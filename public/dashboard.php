<?php

require_once '../infra/verificar_acesso.php';
verificarFuncionario();

require_once '../infra/conexao.php';

$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';
$nome = $_SESSION['usuario_nome'] ?? 'Funcionário';

if (empty($_SESSION['csrf_cep'])) {
    $_SESSION['csrf_cep'] = bin2hex(random_bytes(32));
}

// Consultar trens cadastrados
$sqlTrens = "
    SELECT
        t.id,
        t.nome_linha,
        t.numero_serie,
        t.velocidade,
        t.estado,
        u.nome AS funcionario_responsavel
    FROM trens AS t
    LEFT JOIN usuarios AS u
        ON t.funcionario_id = u.id
    ORDER BY t.id DESC
";

$trens = [];
$erroTrens = "";

try {
    $resultadoTrens = mysqli_query($conn, $sqlTrens);

    while ($registro = mysqli_fetch_assoc($resultadoTrens)) {
        $trens[] = $registro;
    }

    mysqli_free_result($resultadoTrens);

} catch (Throwable $e) {
    error_log($e->getMessage());
    $erroTrens = "Não foi possível carregar os trens.";
}

// Contadores do sistema
$totalTrens = count($trens);
$trensAtivos = 0;
$trensInativos = 0;
$trensManutencao = 0;

foreach ($trens as $trem) {
    switch ($trem['estado']) {
        case 'Ativo':
            $trensAtivos++;
            break;

        case 'Inativo':
            $trensInativos++;
            break;

        case 'Manutencao':
            $trensManutencao++;
            break;
    }
}

// Escapar textos para exibição segura
function escaparDashboard($valor) {
    return htmlspecialchars(
        (string) ($valor ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

// Definir aparência conforme a linha cadastrada
function classeLinhaDashboard($linha) {
    $linha = mb_strtolower((string) $linha, 'UTF-8');

    if (str_contains($linha, 'azul')) {
        return 'linha-azul';
    }

    if (str_contains($linha, 'verde')) {
        return 'linha-verde';
    }

    if (str_contains($linha, 'amarela')) {
        return 'linha-amarela';
    }

    if (str_contains($linha, 'vermelha')) {
        return 'linha-vermelha';
    }

    return 'linha-padrao';
}

// Definir status visual
function classeEstadoDashboard($estado) {
    switch ($estado) {
        case 'Ativo':
            return 'estado-ativo';

        case 'Inativo':
            return 'estado-inativo';

        case 'Manutencao':
            return 'estado-manutencao';

        default:
            return 'estado-desconhecido';
    }
}

function textoEstadoDashboard($estado) {
    if ($estado === 'Manutencao') {
        return 'Manutenção';
    }

    return $estado ?: 'Não informado';
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

    <title>Dashboard ● Strain</title>

    <!-- CSS ORIGINAL -->
    <link
        rel="stylesheet"
        href="../assets/style/style.css"
    >

    <link
        rel="stylesheet"
        href="../assets/style/clima.css"
    >

    <link
        rel="stylesheet"
        href="../assets/style/cep.css"
    >
    <!-- Estilos do Dashboard integrados ao style.css -->

    <!-- JAVASCRIPT DAS APIS -->
    <script src="../Scripts/clima.js" defer></script>
    <script src="../Scripts/cep.js" defer></script>

</head>

<body class="body_dashboard">

    <!-- NAVBAR -->
    <header id="header_dashboard">

        <nav class="navbar">

            <div class="marca-strain">
                <i data-lucide="train-front"></i>
                <span>STRAIN</span>
            </div>

            <div class="saudacao-navbar">
                <p>
                    Olá, <?= escaparDashboard($nome) ?>!
                </p>
            </div>

        </nav>

    </header>

    <!-- SIDEBAR -->
    <aside id="sidebar_dashboard">

        <h2 id="titulo_sidebar">
            Dashboard
        </h2>

        <ul class="menu_sidebar">

            <li>
                <a
                    href="dashboard.php"
                    class="active_sidebar"
                >
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
                        Usuários
                    </a>
                </li>
            <?php } ?>

            <li>
                <a href="sensores/visu_sensores.php">
                    Sensores
                </a>
            </li>

            <li>
                <a href="usuarios/logout.php">
                    Logout
                </a>
            </li>

        </ul>

    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main id="main_dashboard" class="main_dashboard_dinamico">

        <!-- PAINEL VERMELHO COM CARDS DOS TRENS -->
        <section class="secao_trens_dashboard">

            <div class="cabecalho_dashboard">
                <div>
                    <h1>Painel de Monitoramento</h1>
                    <p>Trens Cadastrados</p>
                </div>

                <a
                    href="Trens/visualizar_trens.php"
                    class="link_trens_dashboard"
                >
                    Visualizar Trens
                </a>
            </div>

            <?php if ($erroTrens !== "") { ?>

                <p class="mensagem_dashboard">
                    <?= escaparDashboard($erroTrens) ?>
                </p>

            <?php } elseif (empty($trens)) { ?>

                <p class="mensagem_dashboard">
                    Nenhum trem cadastrado no sistema.
                </p>

            <?php } else { ?>

                <div class="cards_trens_dashboard">

                    <?php foreach ($trens as $trem) { ?>

                        <?php
                        $classeLinha = classeLinhaDashboard(
                            $trem['nome_linha']
                        );

                        $classeEstado = classeEstadoDashboard(
                            $trem['estado']
                        );
                        ?>

                        <article class="card_trem_dashboard <?= $classeLinha ?>">

                            <!-- CABEÇALHO -->
                            <div class="cabecalho_card_trem">

                                <div>
                                    <h3>
                                        Trem
                                        <?= escaparDashboard($trem['numero_serie']) ?>
                                    </h3>

                                    <p class="nome_linha_card">
                                        <?= escaparDashboard($trem['nome_linha']) ?>
                                    </p>
                                </div>

                                <span
                                    class="indicador_estado <?= $classeEstado ?>"
                                    aria-label="Estado: <?= escaparDashboard(textoEstadoDashboard($trem['estado'])) ?>"
                                ></span>

                            </div>

                            <!-- INFORMAÇÕES -->
                            <div class="conteudo_card_trem">

                                <div class="icone_card_trem">
                                    <i data-lucide="train-front"></i>
                                </div>

                                <div class="dados_card_trem">

                                    <span class="rotulo_card">
                                        Velocidade cadastrada
                                    </span>

                                    <strong class="velocidade_card">
                                        <?= escaparDashboard($trem['velocidade']) ?>
                                        <small>km/h</small>
                                    </strong>

                                    <span class="rotulo_card">
                                        Estado
                                    </span>

                                    <span class="status_card <?= $classeEstado ?>">
                                        <?= escaparDashboard(
                                            textoEstadoDashboard($trem['estado'])
                                        ) ?>
                                    </span>

                                </div>

                            </div>

                            <!-- RODAPÉ -->
                            <div class="rodape_card_trem">

                                <p>
                                    <strong>Responsável:</strong>
                                    <?= escaparDashboard(
                                        $trem['funcionario_responsavel']
                                        ?? 'Não informado'
                                    ) ?>
                                </p>

                                <a href="Trens/visualizar_trens.php">
                                    Ver detalhes
                                    <i data-lucide="arrow-right"></i>
                                </a>

                            </div>

                        </article>

                    <?php } ?>

                </div>

            <?php } ?>

        </section>

        <!-- RESUMO DO SISTEMA -->
        <?php if ($erroTrens === "") { ?>
        <section class="secao_resumo_dashboard">

            <h2>Resumo do Sistema</h2>

            <div class="cards_resumo_dashboard">

                <div class="card_resumo_dashboard">
                    <i data-lucide="train-front"></i>
                    <div>
                        <strong><?= $totalTrens ?></strong>
                        <span>Total de trens</span>
                    </div>
                </div>

                <div class="card_resumo_dashboard">
                    <i data-lucide="check-circle"></i>
                    <div>
                        <strong><?= $trensAtivos ?></strong>
                        <span>Ativos</span>
                    </div>
                </div>

                <div class="card_resumo_dashboard">
                    <i data-lucide="pause-circle"></i>
                    <div>
                        <strong><?= $trensInativos ?></strong>
                        <span>Inativos</span>
                    </div>
                </div>

                <div class="card_resumo_dashboard">
                    <i data-lucide="wrench"></i>
                    <div>
                        <strong><?= $trensManutencao ?></strong>
                        <span>Em manutenção</span>
                    </div>
                </div>

            </div>

        </section>
        <?php } ?>

        <!-- APIS DO DASHBOARD -->
        <div class="apis_dashboard">

            <!-- API DE CLIMA -->
            <section class="secao_clima">

                <h2>Clima em Joinville</h2>

                <div class="card_clima">

                    <h3 id="local_clima">
                         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                        </svg> Joinville - SC
                    </h3>

                    <p id="temperatura_clima">
                        Carregando...
                    </p>

                    <p id="condicao_clima">
                        Consultando clima...
                    </p>

                    <p id="data_clima">
                        Aguardando atualização...
                    </p>

                </div>

            </section>

            <!-- CONSULTA DE CEP -->
            <div class="consulta_cep">

                <h3>Consultar Endereço</h3>

                <div class="conjunto">

                    <label for="cep">CEP:</label>

                    <div class="linha_cep">

                        <input
                            type="text"
                            id="cep"
                            name="cep"
                            placeholder="00000-000"
                            maxlength="9"
                            inputmode="numeric"
                        >

                        <button
                            type="button"
                            id="consultar_cep"
                        >
                            Consultar
                        </button>

                    </div>

                </div>

                <div class="conjunto">
                    <label for="logradouro">Logradouro:</label>
                    <input type="text" id="logradouro" readonly>
                </div>

                <div class="conjunto">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" readonly>
                </div>

                <div class="conjunto">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" readonly>
                </div>

                <div class="conjunto">
                    <label for="uf">Estado (UF):</label>
                    <input type="text" id="uf" readonly>
                </div>

                <p
                    id="mensagem_cep"
                    role="status"
                    aria-live="polite"
                ></p>

            </div>

        </div>

    </main>

    <!-- ÍCONES -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>
