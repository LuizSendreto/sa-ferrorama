<?php
// Compartilhado somente pelas listagens de Usuários e Sensores.
if (!isset($modulo) || !in_array($modulo, ['usuarios', 'sensores'], true)) {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/../../infra/verificar_acesso.php';
$ehUsuario = $modulo === 'usuarios';
$ehUsuario ? verificarAdmin() : verificarFuncionario();
require_once __DIR__ . '/../../infra/conexao.php';
$admin = ($_SESSION['nivel_acesso'] ?? '') === 'admin';
$nome = $_SESSION['usuario_nome'] ?? 'Funcionário';
$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
require_once __DIR__ . '/visual.php';
// Paginação no servidor; a mesma quantidade e controles nas duas páginas.
$porPagina = 6;
$total = (int) $conn->query($ehUsuario ? 'SELECT COUNT(*) FROM usuarios' : 'SELECT COUNT(*) FROM sensores')->fetch_row()[0];
$totalPaginas = max(1, (int) ceil($total / $porPagina));
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?: 1;
$pagina = max(1, min($pagina, $totalPaginas));
$offset = ($pagina - 1) * $porPagina;
$sql = $ehUsuario
    ? 'SELECT u.id, u.nome, u.email, u.telefone, u.tipo, u.nivel_acesso, e.cep FROM usuarios u LEFT JOIN enderecos_usuarios e ON e.usuario_id = u.id ORDER BY u.nome ASC, u.id ASC LIMIT ? OFFSET ?'
    : 'SELECT id, tipo, localizacao, posicao FROM sensores ORDER BY id ASC LIMIT ? OFFSET ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $porPagina, $offset);
$stmt->execute();
$registros = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$titulo = $ehUsuario ? 'Usuários' : 'Sensores';
$cadastro = $ehUsuario ? '../funcionarios/cadastro_funcionarios.php' : 'cadastro_sensor.php';
$edicao = $ehUsuario ? 'editar_usuarios.php' : 'editar_sensores.php';
$exclusao = $ehUsuario ? 'excluir_usuarios.php' : 'excluir_sensor.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> • Strain</title>
    <!-- Estilo independente para evitar conflitos com o antigo style.css. -->
    <link rel="stylesheet" href="../../assets/style/listagens.css?v=4">
    <script src="../../Scripts/listagens.js?v=2" defer></script>
</head>
<body class="strain-listagem">
<?php strainNavegacao($titulo, '../', $nome, $admin); ?>
<main class="strain-conteudo">
    <section class="strain-cabecalho" aria-labelledby="titulo-pagina">
        <div><h1 id="titulo-pagina">Visualização de <?= $titulo ?></h1><p><?= $titulo ?> Cadastrados</p></div>
        <?php if ($admin): ?>
            <button class="strain-acao strain-cadastrar" type="button" data-abrir="cadastro" title="<?= $ehUsuario ? 'Cadastrar usuário' : 'Cadastrar sensor' ?>" aria-label="<?= $ehUsuario ? 'Cadastrar usuário' : 'Cadastrar sensor' ?>"><?= icone('plus') ?><span><?= $ehUsuario ? 'Cadastrar usuário' : 'Cadastrar sensor' ?></span></button>
        <?php endif; ?>
    </section>
    <p class="strain-aviso" id="aviso-listagem" role="status" hidden></p>
    <section class="strain-painel" aria-label="Lista de <?= $titulo ?>">
        <div class="strain-tabela-scroll" tabindex="0" role="region" aria-label="Tabela de <?= $titulo ?>">
            <table class="strain-tabela">
                <thead><tr>
                    <?php foreach ($ehUsuario ? ['ID','Nome','E-mail','Telefone','Tipo','Nível de acesso','CEP'] : ['ID','Tipo','Localização','Posição'] as $coluna): ?>
                        <th scope="col"><?= $coluna ?></th>
                    <?php endforeach; ?>
                    <?php if ($admin): ?><th scope="col">Ações</th><?php endif; ?>
                </tr></thead>
                <tbody>
                <?php foreach ($registros as $registro): ?>
                    <tr>
                        <td><?= (int) $registro['id'] ?></td>
                        <?php if ($ehUsuario): ?>
                            <td><?= escapar($registro['nome']) ?></td>
                            <td><?= escapar($registro['email']) ?></td>
                            <td><?= escapar($registro['telefone']) ?></td>
                            <td><?= $registro['tipo'] === 'funcionario' ? 'Funcionário' : 'Cliente' ?></td>
                            <td><?= $registro['nivel_acesso'] === 'admin' ? 'Administrador' : 'Comum' ?></td>
                            <td><?php $cep = preg_replace('/\D/', '', (string) ($registro['cep'] ?? '')); echo $cep === '' ? 'Não cadastrado' : (strlen($cep) === 8 ? escapar(substr($cep,0,5) . '-' . substr($cep,5)) : 'CEP inválido'); ?></td>
                        <?php else: ?>
                            <td><?= escapar($registro['tipo']) ?></td>
                            <td><?= escapar($registro['localizacao']) ?></td>
                            <td><?= escapar($registro['posicao']) ?></td>
                        <?php endif; ?>
                        <?php if ($admin): ?>
                            <td><div class="strain-acoes">
                                <button type="button" class="strain-acao strain-editar" data-editar="<?= escapar(json_encode($registro, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE)) ?>" title="Editar registro <?= (int) $registro['id'] ?>" aria-label="Editar registro <?= (int) $registro['id'] ?>"><?= icone('pencil') ?></button>
                                <?php if (!$ehUsuario || (int) $registro['id'] !== (int) $_SESSION['usuario_id']): ?>
                                    <button type="button" class="strain-acao strain-excluir" data-excluir="<?= (int) $registro['id'] ?>" data-nome="<?= escapar($ehUsuario ? $registro['nome'] : $registro['tipo']) ?>" title="Excluir registro <?= (int) $registro['id'] ?>" aria-label="Excluir registro <?= (int) $registro['id'] ?>"><?= icone('trash') ?></button>
                                <?php endif; ?>
                            </div></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$registros): ?><tr><td colspan="<?= $ehUsuario ? 8 : ($admin ? 5 : 4) ?>" class="strain-vazio">Nenhum registro cadastrado.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $rotuloRegistros = $ehUsuario ? 'usuários' : 'sensores'; require __DIR__ . '/paginacao.php'; ?>
    </section>
</main>
<?php if ($admin): require __DIR__ . '/modais_listagem.php'; endif; ?>

</body>
</html>
