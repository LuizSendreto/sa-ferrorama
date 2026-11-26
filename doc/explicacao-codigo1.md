# Pesquisa de código — Sistema Strain

Material de estudo sobre os trechos de código do projeto. Exemplos incompletos são mantidos como trechos de referência.

## Geração do token CSRF

```php
$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
```

Cria um token caso não exista ou seja nulo. `random_bytes(32)` gera 32 bytes aleatórios seguros e `bin2hex()` os converte em 64 caracteres hexadecimais. O conceito e o fluxo do CSRF estão detalhados na seção sobre `trens/trens_cadastro.php`.

## Cabeçalhos dinâmicos de tabela

```php
<?php foreach ($colunas as $coluna): ?>
    <th scope="col"><?= $coluna ?></th>
<?php endforeach; ?>
```

- `foreach`: percorre o array `$colunas`.
- `<th scope="col">`: representa o cabeçalho de uma coluna.
- `<?= $coluna ?>`: exibe o valor atual.
- `endforeach`: encerra o laço.

## Conversão para JSON e escape

```php
escapar(json_encode($registro, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE))
```

- `json_encode()`: transforma os dados em JSON.
- `JSON_UNESCAPED_UNICODE`: mantém caracteres Unicode, como acentos, sem convertê-los em sequências de escape Unicode.
- `JSON_INVALID_UTF8_SUBSTITUTE`: substitui sequências UTF-8 inválidas.
- `escapar()`: prepara o resultado para exibição segura no HTML, conforme o contexto.

## Resposta de modal

```php
function strainRespostaModal(bool $ok, string $mensagem, int $status = 200): void
{
    if (($_POST['modal'] ?? '') !== '1') {
        return;
    }
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => $ok, 'mensagem' => $mensagem], JSON_UNESCAPED_UNICODE);
    exit;
}
```

A função envia uma resposta JSON para requisições identificadas pelo campo `modal`.

- `$ok`: indica sucesso ou falha.
- `$mensagem`: texto da resposta.
- `$status`: código HTTP (200 por padrão).
- `void`: indica que a função não retorna um valor.
- `return`: encerra apenas a função quando a requisição não é de modal.
- `header()`: informa o tipo de conteúdo da resposta.
- `exit`: encerra a execução após enviar o JSON.

## Consulta ViaCEP

```php
function consultarViaCep($cep) {
    if (!function_exists('curl_init')) {
        throw new RuntimeException(
            "A extensão cURL não está habilitada no PHP."
        );
    }
    $url = "https://viacep.com.br/ws/" . $cep . "/json/";
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true
    ]);
    // Continuação não apresentada no material original.
}
```

O cURL permite realizar requisições HTTP a serviços externos, como o ViaCEP. O trecho verifica a disponibilidade da extensão e configura a consulta.

- `function_exists()`: verifica se a função está disponível.
- `throw new RuntimeException()`: lança uma exceção quando o cURL não está disponível.
- `curl_init()`: inicializa uma sessão cURL.
- `curl_setopt_array()`: configura as opções da sessão.
- `CURLOPT_RETURNTRANSFER`: permite retornar a resposta como texto quando a requisição for executada.
- `CURLOPT_CONNECTTIMEOUT`: limita a conexão a 5 segundos.
- `CURLOPT_TIMEOUT`: limita a requisição a 10 segundos.
- `CURLOPT_SSL_VERIFYPEER`: verifica o certificado do servidor.

## Validação do token CSRF

```php
if (
    !isset($_POST['csrf_token']) ||
    !is_string($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    strainRespostaModal(false, "Solicitação inválida.", http_response_code() >= 400 ? http_response_code() : 422);
    exit("Solicitação inválida.");
}
```

- `isset()`: verifica se o token foi enviado.
- `is_string()`: confirma que o token recebido é texto.
- `hash_equals()`: compara o token recebido com o da sessão de forma resistente a ataques de temporização.
- `403`: indica acesso proibido.

Se alguma condição falhar, a requisição é rejeitada. O trecho pressupõe que o token da sessão já foi criado.

## Normalização do CEP

```php
$cepNumerico = str_replace("-", "", $cep);
$cepAnterior = preg_replace('/\D/', '', (string) ($usuario['cep'] ?? ''));
```

- `str_replace()`: remove o hífen do CEP.
- `preg_replace()`: remove os caracteres que não são dígitos (`\D`) do CEP anterior.
- `(string)`: converte o valor em texto.

## Lucide Icons

```javascript
lucide.createIcons();
```

Identifica os elementos HTML configurados para a biblioteca Lucide e os substitui pelos respectivos ícones SVG.

---

## `Infra/verificar_acesso.php`

### Controle de acesso

```php
// Verificar se está logado
function verificarLogin() {
    global $inicio;
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: " . $inicio);
        exit;
    }
}
// Permitir somente funcionários
function verificarFuncionario() {
    global $inicio;
    verificarLogin();
    if (($_SESSION['tipo_usuario'] ?? '') != 'funcionario') {
        header("Location: " . $inicio);
        exit;
    }
}
// Permitir somente administradores
function verificarAdmin() {
    global $dashboard;
    verificarFuncionario();
    if (($_SESSION['nivel_acesso'] ?? '') != 'admin') {
        header("Location: " . $dashboard);
        exit;
    }
}
```

O código é responsável por verificar as permissões dos usuários dentro do sistema.

#### Funções

- `verificarLogin()`: verifica se o usuário está logado.

- `verificarFuncionario()`: verifica se o usuário está logado e é funcionário.

- `verificarAdmin()`: verifica se o usuário é funcionário e possui nível de acesso administrativo.

#### Principais comandos

- `function`: declara uma função.

- `global`: permite utilizar uma variável externa dentro da função.

- `$_SESSION`: armazena informações do usuário durante a sessão.

- `isset()`: verifica se uma variável existe e não é nula.

- `!`: representa uma negação.

- `!=`: verifica se dois valores são diferentes.

- `??`: utiliza um valor alternativo caso a informação não exista ou seja nula.

- `header()`: redireciona o usuário para outra página, neste código.

- `exit`: interrompe a execução do código.

#### Objetivo

Garantir a segurança do sistema, impedir acessos não autorizados e evitar a repetição de código por meio da reutilização de funções.

#### Conceitos


- **Autenticação:** verifica se o usuário está logado.

- **Autorização:** verifica quais funcionalidades o usuário pode acessar.

- **Hierarquia de acesso:** usuário logado → funcionário → administrador.


---

## `index.php`

### Verificação da sessão do usuário

```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuarioLogadoIndex = !empty($_SESSION['usuario_id']);
$nomeUsuarioIndex = $_SESSION['usuario_nome'] ?? 'usuário';
?>
```

Esse código é responsável por iniciar a sessão, verificar se o usuário está logado e obter seu nome.

#### Principais comandos

- `session_status()`: verifica o estado atual da sessão.

- `PHP_SESSION_NONE`: indica que nenhuma sessão está ativa.

- `===`: compara se dois valores são exatamente iguais, incluindo seus tipos.

- `session_start()`: inicia ou recupera uma sessão existente.

- `$_SESSION`: permite armazenar e acessar informações do usuário entre diferentes páginas.

- `empty()`: verifica se uma variável não existe ou está vazia.

- `!empty()`: verifica se uma variável existe e não está vazia.

- `??`: define um valor alternativo caso a informação não exista ou seja nula.

#### Variáveis

- `$usuarioLogadoIndex`: armazena `true` se existir um ID de usuário válido na sessão (não vazio) e `false` caso contrário.

- `$nomeUsuarioIndex`: armazena o nome do usuário presente na sessão. Caso não exista ou seja nulo, utiliza a palavra "usuário".

#### Para que serve?

Esse código permite que a página inicial reconheça o usuário e apresente, por exemplo, uma mensagem personalizada de boas-vindas. Ele não bloqueia o acesso à página, apenas identifica a situação do usuário.

### `<span>`

É uma tag HTML utilizada para agrupar e estilizar pequenos trechos de texto sem criar uma nova linha.

#### Para que serve?

- Alterar a aparência de textos com CSS.

- Exibir informações de variáveis PHP.

#### Exemplo

```php
<span><?= $nomeUsuarioIndex ?></span>
```

Exibe o nome do usuário na tela.

### `require_once`

É um comando PHP utilizado para incluir e executar outro arquivo apenas uma vez.

#### Para que serve?

- Reutilizar códigos de outros arquivos.

- Evitar inclusões duplicadas.

- Compartilhar funções e configurações entre páginas.

#### Exemplo

```php
require_once 'conexao.php';
```

Inclui o arquivo de conexão com o banco de dados.

Caso o arquivo não seja encontrado, a execução do código é interrompida.

### `<canvas>`

É uma tag HTML utilizada como uma área de desenho na página, geralmente manipulada pelo JavaScript.

#### Para que serve?

- Criar gráficos.

- Desenhar formas e imagens.

- Desenvolver animações.

#### Exemplo

```html
<canvas id="grafico"></canvas>
```

Cria uma área que pode ser utilizada para exibir gráficos através do JavaScript.

### `ctx`

É uma variável geralmente utilizada no JavaScript para armazenar o contexto de desenho de um canvas.

#### Para que serve?

- Criar gráficos.

- Desenhar formas e imagens.

- Manipular o conteúdo do canvas.

#### Exemplo

```javascript
const ctx = document.getElementById('grafico').getContext('2d');
```

- `getElementById()`: localiza o elemento pelo ID.

- `getContext('2d')`: permite realizar desenhos em duas dimensões.

O nome `ctx` é uma abreviação de "context" (contexto).

---

## `dashboard.php`

### Token CSRF

```php
if (empty($_SESSION['csrf_cep'])) {
    $_SESSION['csrf_cep'] = bin2hex(random_bytes(32));
}
```

Esse código cria um token de segurança para proteger o formulário de CEP contra requisições não autorizadas.

#### Principais comandos

- `if`: verifica uma condição.

- `empty()`: verifica se uma informação está vazia ou não existe.

- `$_SESSION['csrf_cep']`: armazena o token de segurança na sessão.

- `random_bytes(32)`: gera 32 bytes aleatórios e seguros.

- `bin2hex()`: converte os bytes em uma sequência de 64 caracteres hexadecimais.

#### Como funciona?

Se o token ainda não existir, o sistema cria um novo e o armazena na sessão. Caso já exista, mantém o mesmo token.

#### Para que serve?

- Proteger formulários contra ataques CSRF.

- Ajudar a impedir requisições não autorizadas.

- Aumentar a segurança do sistema.

Veja também a seção **Token CSRF** em `trens/trens_cadastro.php`.

### Consulta SQL de trens

```php
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
```

Esse código cria uma consulta SQL para buscar os dados dos trens cadastrados e seus respectivos funcionários responsáveis.

#### Principais comandos

- `$sqlTrens`: variável PHP que armazena a consulta SQL.

- `SELECT`: seleciona as informações que serão consultadas.

- `FROM`: indica a tabela principal da consulta.

- `AS`: atribui um nome alternativo a uma tabela ou coluna.

- `LEFT JOIN`: conecta duas tabelas, mantendo todos os registros da tabela principal, mesmo que não exista um funcionário vinculado.

- `ON`: define a condição de relacionamento entre as tabelas.

- `ORDER BY`: organiza os resultados.

- `DESC`: organiza os registros em ordem decrescente.

#### Abreviações

- `t`: representa a tabela `trens`.

- `u`: representa a tabela `usuarios`.

#### Relacionamento

`t.funcionario_id = u.id`

Relaciona o trem ao usuário responsável por ele.

#### Para que serve?

- Consultar os dados dos trens.

- Identificar o funcionário responsável.

- Exibir trens mesmo sem funcionários vinculados.

- Organizar os trens do maior para o menor ID.

---

## `usuarios/cadastro_usuarios.php`

### Verificação da extensão cURL

A verificação e a configuração do cURL estão reunidas na seção **Consulta ViaCEP**.

---

## `usuarios/editar_usuarios.php`

### `!preg_match()`

```php
!preg_match('/^\d{5}-?\d{3}$/D', $cep)
```

É uma função PHP utilizada para verificar se uma informação segue determinado padrão por meio de uma expressão regular (Regex).

#### Significado dos comandos

- `!`: representa uma negação.

- `preg_match()`: verifica se o valor corresponde ao padrão.

- `^`: indica o início da sequência.

- `\d`: representa um dígito numérico.

- `{5}`: exige exatamente cinco dígitos.

- `-?`: indica que o hífen é opcional.

- `{3}`: exige exatamente três dígitos.

- `$`: indica o final da sequência.

- `D`: exige que o final corresponda ao fim real do texto.

#### Para que serve?

Verificar se o CEP informado possui um formato inválido.

#### Exemplos

- 89200-000: válido.

- 89200000: válido.

- 89200: inválido.

- ABC12345: inválido.

---

## `usuarios/login.php`

### Throwable

É uma interface do PHP que permite capturar erros (Error) e exceções (Exception) durante a execução do código.

#### Para que serve?

- Tratar erros e exceções.

- Evitar interrupções inesperadas do sistema.

- Exibir mensagens de erro personalizadas.

#### Exemplo

```php
try {
    consultarCep($cep);
} catch (Throwable $e) {
    echo $e->getMessage();
}
```

#### Principais comandos

- `try`: executa um código que pode apresentar erros.

- `catch`: captura o erro ou a exceção.

- `Throwable`: permite capturar tanto erros quanto exceções.

- `$e`: variável que armazena o erro capturado.

- `getMessage()`: retorna a mensagem do erro.

---

## `trens/trens_cadastro.php`

### Token CSRF

É um código de segurança único e imprevisível utilizado para proteger formulários contra requisições fraudulentas.

CSRF significa Cross-Site Request Forgery (Falsificação de Requisição entre Sites).

#### Para que serve?

- Proteger os formulários do sistema.

- Impedir ações não autorizadas feitas em nome do usuário.

- Aumentar a segurança das requisições.

#### Como funciona?

1. O sistema gera um token e o armazena na sessão.

2. O token é enviado junto ao formulário.

3. O sistema compara o token recebido com o armazenado.

4. Se forem diferentes ou estiverem ausentes, a requisição é rejeitada.

### `htmlspecialchars()`

```html
value="<?php echo htmlspecialchars(
                        $_SESSION['csrf_token'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>"
```

Esse código insere o token CSRF armazenado na sessão no atributo `value` de um campo HTML, de forma segura.

#### Principais comandos

- `value`: define o valor de um campo HTML.

- `echo`: exibe uma informação.

- `htmlspecialchars()`: converte caracteres especiais em entidades HTML, evitando que sejam interpretados como código HTML.

- `$_SESSION['csrf_token']`: recupera o token CSRF armazenado na sessão.

- `ENT_QUOTES`: converte aspas simples e duplas em entidades HTML.

- `UTF-8`: define a codificação dos caracteres.

#### Para que serve?

- Inserir o token CSRF no formulário.

- Evitar problemas com caracteres especiais.

- Ajudar a prevenir ataques de injeção de código HTML (XSS).

- Permitir que o token seja enviado para validação.

---

## `componentes/listagem_operacional.php`

### Escapar caracteres

Escapar significa transformar caracteres especiais em uma representação segura, evitando que sejam interpretados como código.

#### Para que serve?

- Proteger o sistema contra ataques XSS.

- Evitar que textos sejam interpretados como código HTML.

- Exibir informações de forma segura.

#### Exemplo em PHP

```php
htmlspecialchars($nome);
```

A função converte caracteres especiais, como:

- `<` em `&lt;`

- `>` em `&gt;`

- `&` em `&amp;`

Dessa forma, o navegador interpreta a informação como texto, e não como código HTML.

---

## `componentes/listagem.php`

### Validação de módulos

```php
if (!isset($modulo) || !in_array($modulo, ['usuarios', 'sensores'], true)) {
    http_response_code(404);
    exit;
}
```

Esse código verifica se o módulo solicitado existe como variável e está entre os módulos permitidos pelo sistema.

#### Principais comandos

- `isset()`: verifica se a variável existe e não é nula.

- `!`: representa uma negação.

- `||`: significa "OU".

- `in_array()`: verifica se um valor está presente em um array.

- `true`: exige que o valor e o tipo de dado sejam iguais na comparação.

- `http_response_code(404)`: retorna o código HTTP 404 (Não encontrado).

- `exit`: interrompe a execução do código.

#### Como funciona?

Se a variável `$modulo` não estiver definida ou não corresponder a `usuarios` ou `sensores`, o sistema retorna o código 404 e interrompe a execução.

#### Para que serve?

- Validar os módulos solicitados.

- Impedir o processamento de módulos não permitidos.

- Aumentar a segurança do sistema.

---

## `componentes/paginacao.php`

### Exibição da paginação

```html
<p>Mostrando <?= $total ?  $offset + 1 : 0 ?> a <?= min($offset + $porPagina, $total) ?> de <?= $total ?> <?= $rotuloRegistros ?></p>
```

Esse código exibe a quantidade de registros que estão sendo apresentados na página.

#### Principais comandos

- `<p>`: cria um parágrafo HTML.

- `<?= ?>`: forma simplificada do `echo` no PHP.

- `? :`: operador ternário, uma forma simplificada do if e else.

- `min()`: retorna o menor valor entre os valores informados.

#### Variáveis

- `$total`: quantidade total de registros cadastrados.

- `$offset`: quantidade de registros anteriores à página atual.

- `$porPagina`: quantidade de registros permitidos por página.

- `$rotuloRegistros`: nome dos registros, como usuários ou sensores.

#### Como funciona?

- `$total ? $offset + 1 : 0`: define o número do primeiro registro exibido ou mostra zero caso não existam registros.

- `min($offset + $porPagina, $total)`: define o número do último registro exibido, sem ultrapassar o total.

#### Para que serve?

Informar ao usuário quais registros estão sendo exibidos na página atual.

#### Exemplo de resultado

Mostrando 1 a 10 de 35 usuários.

---

## `componentes/visual.php`

### Função icone()

```php
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
```

Essa função PHP é responsável por gerar e exibir ícones no formato SVG dentro do sistema.

#### Principais comandos

- `function icone()`: declara a função.

- `string $nome`: determina que o parâmetro recebido deve ser um texto.

- `: string`: determina que a função deve retornar um texto.

- `===`: compara o valor e o tipo da informação.

- `$paths`: array associativo que armazena os nomes e desenhos dos ícones.

- `=>`: associa uma chave a um valor dentro do array.

- `return`: retorna o resultado da função.

- `?? ''`: retorna um texto vazio caso o ícone não exista.

- `.`: concatena (une) textos no PHP.

#### Elementos SVG

- `<svg>`: cria uma área para o desenho vetorial.

- `<path>`: desenha linhas e formas.

- `<circle>`: desenha círculos.

- `<rect>`: desenha retângulos.

- `width` e `height`: definem o tamanho do ícone.

- `viewBox`: define o sistema de coordenadas do desenho.

- `fill="none"`: remove o preenchimento.

- `stroke="currentColor"`: utiliza a cor atual do texto no contorno.

- `stroke-width`: define a espessura do contorno.

- `aria-hidden="true"`: oculta o ícone dos leitores de tela.

#### Exemplo de utilização

```php
<?= icone('train') ?>
```

Exibe o ícone de um trem.

#### Para que serve?

- Centralizar os ícones do sistema.

- Evitar a repetição de código SVG.

- Padronizar o tamanho e a aparência dos ícones.

- Facilitar a manutenção do sistema.

### Função `escapar()`

```php
function escapar($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
```

A função converte o valor em texto e escapa caracteres especiais para uso no HTML. `ENT_SUBSTITUTE` substitui sequências inválidas de caracteres. Veja também a seção **Escapar caracteres**.

### Função `strainNavegacao()`

```php
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
    <a href="<?= escapar($base . $link) ?>" <?= $rotulo === $ativo ? 'aria-current="page"' : '' ?> <?= $rotulo === 'Logout'?'data-logout':''?>><?=icone($simbolo) ?><span><?= $rotulo ?></span></a>
<?php endforeach; ?>
</nav></aside>
<dialog class="strain-modal strain-modal-pequeno" id="modal-logout" aria-labelledby="titulo-logout">
    <div class="strain-modal-topo"><h2 id="titulo-logout">Sair do sistema?</h2><button type="button" class="strain-fechar" data-fechar aria-label="Fechar"><?= icone('close') ?></button></div>
    <p>Sua sessão será encerrada.</p>
    <div class="strain-modal-rodape"><button type="button" class="strain-btn strain-neutro" data-fechar>Cancelar</button><a class="strain-btn strain-perigo" href="<?= escapar($base) ?>usuarios/logout.php">Sair</a></div>
</dialog>
<?php
}
```

A função monta o cabeçalho, o menu lateral e o modal de confirmação de logout. Recebe a página ativa, o caminho base, o nome do usuário e a indicação de administrador. O `foreach` percorre o menu e `continue` ignora o item Usuários quando não há permissão administrativa. `aria-current="page"` identifica a página atual. O modal permite cancelar ou confirmar a saída.

---

## Stmt (Prepared Statements)

Prepared Statements são uma forma segura de executar consultas SQL em PHP, ajudando a evitar **SQL Injection**. Basicamente, eles impedem que dados inseridos pelo usuário alterem a estrutura do código SQL. Em vez de inserir diretamente os valores na consulta, o sistema utiliza parâmetros que são preenchidos posteriormente.

Primeiro, o MySQL prepara a estrutura da consulta e, em seguida, recebe os valores por meio de parâmetros, tornando a operação mais segura.

### Exemplo:

```php
<?php
include "../infra/conexao.php";
$titulo = $_POST["titulo"];
$autor = $_POST["autor"];
$ano = $_POST["ano"];
$sql = "INSERT INTO livros (titulo, autor, ano) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ssi", $titulo, $autor, $ano);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
```

### Explicação:

- **mysqli_prepare():** prepara a consulta SQL antes de receber os valores.

- **mysqli_stmt_bind_param():** associa os valores às interrogações (`?`) da consulta. As letras `"ssi"` indicam os tipos dos parâmetros: string, string e inteiro.

- **mysqli_stmt_execute():** executa a consulta com os valores associados.

- **mysqli_stmt_close():** encerra a instrução preparada.

## Chart.js

O Chart.js é uma biblioteca JavaScript utilizada para criar gráficos em páginas web, permitindo apresentar informações de maneira visual e organizada.

### Exemplo:

```javascript
const ctx = document.getElementById('graficoVelocidade');
if (ctx && typeof Chart !== 'undefined') {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                '09:55',
                '10:00',
                '10:05',
                '10:10',
                '10:15',
                '10:20'
            ],
            // Continuação não apresentada no material original.
```

### Explicação:

- **if:** verifica se as condições necessárias para criar o gráfico foram atendidas.

- **ctx:** identifica o elemento HTML onde o gráfico será desenhado.

- **typeof Chart:** verifica se a biblioteca Chart.js está disponível.

- **new Chart():** cria uma nova instância do gráfico.

- **type: 'line':** define o tipo de gráfico como gráfico de linhas.

- **data:** armazena as informações que serão utilizadas no gráfico.

- **labels:** define os rótulos do eixo X, que, nesse exemplo, representam os horários das medições.

## Validação de dados

Esse trecho de código PHP é responsável por realizar a **validação dos dados antes de cadastrar um trem no banco de dados**. Seu objetivo é garantir que todas as informações estejam corretas antes de prosseguir com o cadastro.

### Exemplo:

```php
if (
    $nome_linha === "" ||
    $numero_serie === "" ||
    $velocidade === ""
) {
    $erro = "Preencha todos os campos.";
} elseif (!in_array($nome_linha, $linhas_validas, true)) {
    $erro = "Selecione uma linha válida.";
} elseif (!ctype_digit($velocidade)) {
    $erro = "A velocidade deve ser um número inteiro maior ou igual a zero.";
} elseif (!in_array($estado, $estados_validos, true)) {
    $erro = "Status inválido.";
} elseif (
    !ctype_digit((string) $funcionario_id) ||
    (int) $funcionario_id <= 0
) {
    $erro = "Selecione um funcionário.";
} else {
    $velocidade_int = (int) $velocidade;
    $funcionario_int = (int) $funcionario_id;
    try {
        // Verificar funcionário responsável
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id FROM usuarios
             WHERE id = ? AND tipo = 'funcionario'"
        );
```

### Explicação:

O código verifica se os campos obrigatórios foram preenchidos. Em seguida, confere se a linha escolhida é válida, se a velocidade contém apenas números inteiros não negativos e se o status está entre as opções permitidas. Também verifica se o ID do funcionário é um número inteiro positivo.

Se alguma informação estiver incorreta, o sistema apresenta uma mensagem de erro. Caso todas as validações sejam aprovadas, os valores são convertidos para números inteiros e o código utiliza o **try** para iniciar as operações no banco de dados.

Em seguida, o **mysqli_prepare()** prepara uma consulta SQL para verificar se o funcionário selecionado realmente existe e possui o tipo de usuário correto.

### Principais funções utilizadas:

- **in_array():** verifica se determinado valor existe dentro de um array.

- **ctype_digit():** verifica se uma string contém somente algarismos numéricos.

- **(string):** converte um valor para texto.

- **(int):** converte um valor para número inteiro.

- **try:** permite executar operações que podem gerar exceções, possibilitando tratar possíveis erros.

- **mysqli_prepare():** prepara uma consulta SQL para receber parâmetros com segurança.
