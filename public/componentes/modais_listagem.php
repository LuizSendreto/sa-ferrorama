<?php
if (!isset($admin, $ehUsuario) || !$admin) { http_response_code(404); exit; }
// Incluído somente depois da autorização da listagem.
foreach (['cadastro', 'edicao'] as $modo):
    $criando = $modo === 'cadastro';
    $tituloModal = ($criando ? 'Cadastrar ' : 'Editar ') . ($ehUsuario ? ($criando ? 'funcionário' : 'usuário') : 'sensor');
?>
<dialog class="strain-modal" id="modal-<?= $modo ?>" aria-labelledby="titulo-<?= $modo ?>">
    <form action="<?= escapar($criando ? $cadastro : $edicao) ?>" method="post" data-form-modal>
        <div class="strain-modal-topo"><div><h2 id="titulo-<?= $modo ?>"><?= $tituloModal ?></h2><p>Preencha os dados abaixo para salvar.</p></div><button type="button" class="strain-fechar" data-fechar aria-label="Fechar"><?= icone('close') ?></button></div>
        <input type="hidden" name="csrf_token" value="<?= escapar($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="modal" value="1">
        <?php if (!$criando): ?><input type="hidden" name="id"><?php endif; ?>
        <p class="strain-erro" role="alert" hidden></p>
        <div class="strain-campos">
        <?php if ($ehUsuario): ?>
            <label class="strain-campo-largo" for="<?= $modo ?>-nome">Nome<input id="<?= $modo ?>-nome" name="<?= $criando ? 'nomeUsuario' : 'nome' ?>" maxlength="100" autocomplete="name" required></label>
            <label for="<?= $modo ?>-email">E-mail<input id="<?= $modo ?>-email" name="email" type="email" maxlength="100" autocomplete="email" required></label>
            <label for="<?= $modo ?>-telefone">Telefone<input id="<?= $modo ?>-telefone" name="telefone" type="tel" maxlength="20" autocomplete="tel" required></label>
            <label for="<?= $modo ?>-cep">CEP <?= $criando ? '' : '(opcional)' ?><input id="<?= $modo ?>-cep" name="cep" maxlength="9" inputmode="numeric" pattern="[0-9]{5}-?[0-9]{3}" placeholder="00000-000" <?= $criando ? 'required' : '' ?>><small><?= $criando ? 'O endereço será consultado no ViaCEP ao salvar.' : 'Deixe vazio para manter o CEP atual.' ?></small></label>
            <?php if ($criando): ?>
                <p class="strain-ajuda">O cadastro cria um funcionário com nível de acesso comum.</p>
                <label for="cadastro-senha">Senha<input id="cadastro-senha" name="senha" type="password" minlength="8" autocomplete="new-password" required></label>
                <label for="cadastro-confirmar">Confirmar senha<input id="cadastro-confirmar" name="confirmarSenha" type="password" minlength="8" autocomplete="new-password" required></label>
            <?php else: ?>
                <label for="edicao-tipo">Tipo de usuário<select id="edicao-tipo" name="tipo" required><option value="cliente">Cliente</option><option value="funcionario">Funcionário</option></select></label>
                <label for="edicao-nivel">Nível de acesso<select id="edicao-nivel" name="nivel_acesso" required><option value="comum">Comum</option><option value="admin">Administrador</option></select></label>
            <?php endif; ?>
        <?php else:
            $opcoes = [
                'tipo_sensor' => ['Tipo', ['Temperatura'=>'Temperatura','Vibracao'=>'Vibração','Pressao'=>'Pressão','Umidade'=>'Umidade']],
                'localizacao' => ['Localização', ['Locomotiva'=>'Locomotiva','Vagao 1'=>'Vagão 1','Vagao 2'=>'Vagão 2','Vagao 3'=>'Vagão 3']],
                'posicao' => ['Posição', ['Ativo'=>'Ativo','Inativo'=>'Inativo','Manutencao'=>'Manutenção']]
            ];
            foreach ($opcoes as $campo => [$rotulo, $valores]): ?>
                <label class="strain-campo-largo" for="<?= $modo . '-' . $campo ?>"><?= $rotulo ?><select id="<?= $modo . '-' . $campo ?>" name="<?= $campo ?>" required><option value="">Selecione</option><?php foreach ($valores as $valor => $texto): ?><option value="<?= escapar($valor) ?>"><?= $texto ?></option><?php endforeach; ?></select></label>
            <?php endforeach; endif; ?>
        </div>
        <div class="strain-modal-rodape"><button type="button" class="strain-btn strain-neutro" data-fechar>Cancelar</button><button type="submit" class="strain-btn strain-salvar"><?= $criando ? 'Cadastrar' : 'Salvar alterações' ?></button></div>
    </form>
</dialog>
<?php endforeach; ?>
<dialog class="strain-modal strain-modal-pequeno" id="modal-excluir" aria-labelledby="titulo-excluir">
    <form action="<?= escapar($exclusao) ?>" method="post" data-form-modal>
        <div class="strain-modal-topo"><h2 id="titulo-excluir">Excluir registro?</h2><button type="button" class="strain-fechar" data-fechar aria-label="Fechar"><?= icone('close') ?></button></div>
        <input type="hidden" name="id">
        <input type="hidden" name="csrf_token" value="<?= escapar($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="modal" value="1">
        <p class="strain-erro" role="alert" hidden></p>
        <p>Deseja excluir <strong id="nome-exclusao"></strong>? Esta ação não pode ser desfeita.</p>
        <div class="strain-modal-rodape"><button type="button" class="strain-btn strain-neutro" data-fechar>Cancelar</button><button type="submit" class="strain-btn strain-perigo">Excluir</button></div>
    </form>
</dialog>
