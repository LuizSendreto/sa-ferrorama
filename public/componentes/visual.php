<?php
function escapar($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function icone(string $nome): string {
    if ($nome === 'train-front') $nome = 'train';
    $paths = [
        'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/>',
        'pause-circle' => '<circle cx="12" cy="12" r="9"/><path d="M9 8v8m6-8v8"/>',
        'wrench' => '<path d="m14 6 4 4 4-4a7 7 0 0 1-9 9l-6 6-4-4 6-6a7 7 0 0 1 9-9z"/>',
        'arrow-right' => '<path d="M3 12h18m-7-7 7 7-7 7"/>',
        'thermometer' => '<path d="M9 14V5a3 3 0 0 1 6 0v9a5 5 0 1 1-6 0M12 8v10"/>',
        'activity' => '<path d="M2 12h5l3-9 4 18 3-9h5"/>',
        'zap' => '<path d="m13 2-9 12h7l-1 8 10-13h-7z"/>',
        'disc' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/>',
        'train' => '<rect x="5" y="3" width="14" height="15" rx="4"/><path d="M5 11h14M8 3V1m8 2V1M8 18l-3 4m11-4 3 4M8 21h8"/><path d="M8 14h1m6 0h1"/>',
        'home' => '<path d="m3 10 9-7 9 7M5 9v12h14V9M9 21v-8h6v8"/>',
        'route' => '<circle cx="6" cy="5" r="2"/><circle cx="18" cy="19" r="2"/><path d="M8 5h8a4 4 0 0 1 0 8H8a3 3 0 0 0 0 6h8"/>',
        'monitor' => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M12 17v4m-4 0h8"/>',
        'bell' => '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>',
        'file' => '<path d="M14 2H5v20h14V7zM14 2v5h5M8 12h8m-8 4h6"/>',
        'users' => '<circle cx="9" cy="7" r="4"/><path d="M2 21v-3a7 7 0 0 1 14 0v3M17 3a4 4 0 0 1 0 8m2 3a6 6 0 0 1 3 5v2"/>',
        'wifi' => '<path d="M2 8a16 16 0 0 1 20 0M5 12a11 11 0 0 1 14 0m-11 4a6 6 0 0 1 8 0"/><circle cx="12" cy="20" r="1"/>',
        'logout' => '<path d="M9 3H3v18h6m5-14 5 5-5 5m-7-5h14"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 22v-2a8 8 0 0 1 16 0v2"/>',
        'plus' => '<path d="M12 4v16M4 12h16"/>',
        'pencil' => '<path d="m16 3 5 5-12 12-6 1 1-6zM13 6l5 5"/>',
        'trash' => '<path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7m4-7v7"/>',
        'close' => '<path d="m6 6 12 12M6 18 18 6"/>',
        'left' => '<path d="m15 5-7 7 7 7"/>',
        'right' => '<path d="m9 5 7 7-7 7"/>'
    ];
    return '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$nome] ?? '') . '</svg>';
}

function strainNavegacao(string $ativo, string $base, string $nome, bool $admin): void {
    $menu = [
        ['Dashboard', 'dashboard.php', 'home'], ['Trens', 'Trens/visualizar_trens.php', 'train'],
        ['Rotas', 'rotas/rotas.php', 'route'], ['Monitoramento', 'monitoramento.php', 'monitor'],
        ['Alertas', 'alertas.php', 'bell'], ['Relatórios', 'relatorios.php', 'file'],
        ['Usuários', 'usuarios/visu_usuarios.php', 'users'], ['Sensores', 'sensores/visu_sensores.php', 'wifi'],
        ['Logout', 'usuarios/logout.php', 'logout']
    ];
?>
<header class="strain-topo">
    <a href="<?= escapar($base) ?>dashboard.php" class="strain-marca"><?= icone('train') ?><span>STRAIN</span></a>
    <div class="strain-perfil"><?= icone('user') ?><div>Olá, <?= escapar($nome) ?>!<span><?= $admin ? 'Administrador' : 'Funcionário' ?></span></div></div>
</header>
<aside class="strain-sidebar"><nav aria-label="Menu principal">
<?php foreach ($menu as [$rotulo, $link, $simbolo]): if ($rotulo === 'Usuários' && !$admin) continue; ?>
    <a href="<?= escapar($base . $link) ?>" <?= $rotulo === $ativo ? 'aria-current="page"' : '' ?> <?= $rotulo === 'Logout' ? 'data-logout' : '' ?>><?= icone($simbolo) ?><span><?= $rotulo ?></span></a>
<?php endforeach; ?>
</nav></aside>
<dialog class="strain-modal strain-modal-pequeno" id="modal-logout" aria-labelledby="titulo-logout">
    <div class="strain-modal-topo"><h2 id="titulo-logout">Sair do sistema?</h2><button type="button" class="strain-fechar" data-fechar aria-label="Fechar"><?= icone('close') ?></button></div>
    <p>Sua sessão será encerrada.</p>
    <div class="strain-modal-rodape"><button type="button" class="strain-btn strain-neutro" data-fechar>Cancelar</button><a class="strain-btn strain-perigo" href="<?= escapar($base) ?>usuarios/logout.php">Sair</a></div>
</dialog>
<?php
}
