# Pesquisa de código — Sistema Strain


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

CSRF significa *Cross-Site Request Forgery* (Falsificação de Requisição entre Sites).

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

```php
if (!function_exists('curl_init')) {
        throw new RuntimeException(
            "A extensão cURL não está habilitada no PHP."
        );
}
```

Esse código verifica se a extensão cURL está disponível no PHP.

#### Principais comandos

- `function_exists()`: verifica se uma função existe.
- `curl_init()`: inicializa uma comunicação cURL.
- `throw`: lança uma exceção.
- `RuntimeException`: representa um erro ocorrido durante a execução do sistema.

#### O que é cURL?

É uma ferramenta que permite realizar requisições HTTP e obter informações de serviços externos, como APIs.

#### Para que serve?

Verificar se o PHP possui suporte ao cURL antes de realizar uma requisição. Caso não possua, o código lança uma exceção com uma mensagem explicativa.

---

## `usuarios/editar_usuarios.php`

### `!preg_match()`

```php
!preg_match('/^\d{5}-?\d{3}$/D', $cep)
```

É uma função PHP utilizada para verificar se uma informação segue determinado padrão por meio de uma expressão regular (Regex).

#### Significado dos comandos

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

### Validação do token CSRF

O formulário de cadastro de trens utiliza a mesma proteção CSRF descrita na seção do dashboard.

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

- `||`: significa "OU".
- `in_array()`: verifica se um valor está presente em um array.
- `true`: exige que o valor e o tipo de dado sejam iguais na comparação.
- `http_response_code(404)`: retorna o código HTTP 404 (Não encontrado).

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
<p>Mostrando <?= $total ? $offset + 1 : 0 ?> a <?= min($offset + $porPagina, $total) ?> de <?= $total ?> <?= $rotuloRegistros ?></p>
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
