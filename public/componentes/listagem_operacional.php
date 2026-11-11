<?php
if (!isset($modulo) || !in_array($modulo, ['trens','rotas'], true)) { http_response_code(404); exit; }
require_once __DIR__ . '/../../infra/verificar_acesso.php';
verificarFuncionario();
require_once __DIR__ . '/../../infra/conexao.php';
require_once __DIR__ . '/visual.php';
$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';
$nome = $_SESSION['usuario_nome'] ?? 'Funcionário';
$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
$ehTrem = $modulo === 'trens';
$titulo = $ehTrem ? 'Trens' : 'Rotas';
$porPagina = 6;
$total = (int) $conn->query($ehTrem ? 'SELECT COUNT(*) FROM trens' : 'SELECT COUNT(*) FROM rotas')->fetch_row()[0];
$totalPaginas = max(1, (int) ceil($total / $porPagina));
$pagina = max(1, min(filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?: 1, $totalPaginas));
$offset = ($pagina - 1) * $porPagina;
$sql = $ehTrem
    ? "SELECT t.*, u.nome AS funcionario_responsavel FROM trens t LEFT JOIN usuarios u ON t.funcionario_id = u.id AND u.tipo = 'funcionario' ORDER BY t.id LIMIT ? OFFSET ?"
    : 'SELECT r.*, t.nome_linha, t.numero_serie FROM rotas r LEFT JOIN trens t ON r.trem_id = t.id ORDER BY r.id LIMIT ? OFFSET ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $porPagina, $offset);
$stmt->execute();
$registros = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$cadastro = $ehTrem ? 'trens_cadastro.php' : 'cadastrar_rotas.php';
$edicao = $ehTrem ? 'editar_trens.php' : 'editar_rotas.php';
$exclusao = $ehTrem ? 'excluir_trens.php' : 'excluir_rotas.php';
$colunas = $ehTrem ? ['ID','Linha','Número de série','Velocidade','Estado','Funcionário responsável'] : ['ID','Nome da rota','Descrição','Distância','Trem vinculado','Tempo previsto','Data de criação'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> • Strain</title>
    <link rel="stylesheet" href="../../assets/style/listagens.css?v=4">
    <link rel="stylesheet" href="../../assets/style/admin_paginas.css?v=4">
    <script src="../../Scripts/listagens.js?v=2" defer></script>
    <script src="../../Scripts/formulario_modal.js?v=2" defer></script>
</head>
<body class="strain-listagem strain-admin">
<?php strainNavegacao($titulo, '../', $nome, $admin); ?>
<main class="strain-conteudo">
    <section class="strain-cabecalho"><div><h1>Visualização de <?= $titulo ?></h1><p><?= $titulo ?> <?= $ehTrem ? 'Cadastrados' : 'Cadastradas' ?></p></div>
        <?php if ($admin): ?><a class="strain-acao strain-cadastrar" href="<?= $cadastro ?>" data-formulario-modal data-titulo="Cadastrar <?= $ehTrem ? 'trem' : 'rota' ?>" title="Cadastrar <?= $ehTrem ? 'trem' : 'rota' ?>" aria-label="Cadastrar <?= $ehTrem ? 'trem' : 'rota' ?>"><?= icone('plus') ?></a><?php endif; ?>
    </section>
    <p class="strain-aviso" id="aviso-listagem" role="status" hidden></p>
    <div class="strain-painel">
        <div class="strain-tabela-scroll" tabindex="0" role="region" aria-label="Tabela de <?= $titulo ?>">
            <table class="strain-tabela"><thead><tr>
                <?php foreach ($colunas as $coluna): ?><th scope="col"><?= $coluna ?></th><?php endforeach; ?>
                <?php if ($admin): ?><th scope="col">Ações</th><?php endif; ?>
            </tr></thead><tbody>
                <?php foreach ($registros as $registro): ?><tr>
                    <td><?= (int) $registro['id'] ?></td>
                    <?php if ($ehTrem): ?>
                        <td><?= escapar($registro['nome_linha']) ?></td><td><?= escapar($registro['numero_serie']) ?></td>
                        <td><?= escapar($registro['velocidade']) ?> km/h</td><td><?= escapar($registro['estado'] === 'Manutencao' ? 'Manutenção' : $registro['estado']) ?></td>
                        <td><?= escapar($registro['funcionario_responsavel'] ?? 'Não atribuído') ?></td>
                    <?php else: ?>
                        <td><?= escapar($registro['nome_rota']) ?></td><td><?= escapar($registro['descricao']) ?></td>
                        <td><?= escapar($registro['distancia']) ?> km</td><td><?= escapar($registro['nome_linha'] ? $registro['numero_serie'] . ' - ' . $registro['nome_linha'] : 'Sem trem') ?></td>
                        <td><?= (int) $registro['tempo_estimado'] ?> min</td><td><?= escapar(date('d/m/Y', strtotime($registro['data_criacao']))) ?></td>
                    <?php endif; ?>
                    <?php if ($admin): ?><td><div class="strain-acoes">
                        <a class="strain-acao strain-editar" href="<?= $edicao ?>?id=<?= (int) $registro['id'] ?>" data-formulario-modal data-titulo="Editar <?= $ehTrem ? 'trem' : 'rota' ?>" title="Editar registro <?= (int) $registro['id'] ?>" aria-label="Editar registro <?= (int) $registro['id'] ?>"><?= icone('pencil') ?></a>
                        <form action="<?= $exclusao ?>" method="post" data-confirmar-exclusao="<?= $ehTrem ? 'Excluir este trem e suas rotas vinculadas?' : 'Excluir esta rota?' ?>">
                            <input type="hidden" name="id" value="<?= (int) $registro['id'] ?>"><input type="hidden" name="csrf_token" value="<?= escapar($_SESSION['csrf_token']) ?>">
                            <button type="submit" class="strain-acao strain-excluir" title="Excluir registro <?= (int) $registro['id'] ?>" aria-label="Excluir registro <?= (int) $registro['id'] ?>"><?= icone('trash') ?></button>
                        </form>
                    </div></td><?php endif; ?>
                </tr><?php endforeach; ?>
                <?php if (!$registros): ?><tr><td colspan="<?= count($colunas) + (int) $admin ?>" class="strain-vazio">Nenhum registro cadastrado.</td></tr><?php endif; ?>
            </tbody></table>
        </div>
        <?php $rotuloRegistros = $modulo; require __DIR__ . '/paginacao.php'; ?>
    </div>
    <?php if ($ehTrem && $registros): ?>
    <details class="strain-cards-operacionais"><summary>Resumo dos trens desta página</summary><div class="cards_trens_dashboard">
        <?php foreach ($registros as $trem): ?>
            <article class="card_trem_dashboard"><h3>Trem <?= escapar($trem['numero_serie']) ?> — <?= escapar($trem['nome_linha']) ?></h3>
                <div class="conteudo_card_trem"><div class="icone_card_trem"><?= icone('train') ?></div><div><span class="rotulo_card">Velocidade cadastrada</span><br><strong class="velocidade_card"><?= escapar($trem['velocidade']) ?> <small>km/h</small></strong><p>Estado: <?= escapar($trem['estado'] === 'Manutencao' ? 'Manutenção' : $trem['estado']) ?></p></div></div>
            </article>
        <?php endforeach; ?>
    </div></details>
    <?php endif; ?>
</main>
<?php if ($admin): ?>
<dialog class="strain-modal strain-form-remoto" id="modal-formulario" aria-labelledby="titulo-formulario">
    <div class="strain-modal-topo"><h2 id="titulo-formulario">Formulário</h2><button type="button" class="strain-fechar" data-fechar aria-label="Fechar"><?= icone('close') ?></button></div>
    <p class="strain-erro" role="alert" hidden></p><div id="conteudo-formulario" aria-live="polite"></div>
</dialog>
<?php endif; ?>
</body></html>
