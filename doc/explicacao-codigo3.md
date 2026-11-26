# Explicações dos códigos

## 1. Criação do token CSRF

```php
$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
```

Essa linha verifica se já existe um token CSRF salvo na sessão. O operador `??` significa que, caso não exista, será criado um novo. A função `random_bytes(32)` gera 32 bytes aleatórios, e `bin2hex()` transforma esses bytes em uma sequência hexadecimal.

## 2. Percorrendo um array com `foreach`

```php
<?php foreach ($colunas as $coluna): ?>
    <th scope="col"><?= $coluna ?></th>
<?php endforeach; ?>
```

Esse trecho percorre o array `$colunas` usando o `foreach`. A cada repetição, o valor atual é armazenado na variável `$coluna` e exibido dentro de uma tag `<th>`, que representa o cabeçalho de uma coluna de tabela. O `<?= $coluna ?>` exibe o conteúdo da variável, enquanto `endforeach` encerra o loop.

## 3. Convertendo dados para JSON e escapando o resultado

```php
escapar(json_encode(
    $registro,
    JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
));
```

Esse trecho transforma o conteúdo de `$registro` em uma string JSON usando `json_encode()`. O `JSON_UNESCAPED_UNICODE` mantém caracteres especiais, como acentos, sem convertê-los em códigos, enquanto `JSON_INVALID_UTF8_SUBSTITUTE` substitui caracteres com codificação inválida. Por fim, `escapar()` protege o resultado contra caracteres que poderiam causar problemas ou vulnerabilidades ao inserir esses dados no HTML.

## 4. Enviando uma resposta para um modal

```php
function strainRespostaModal(bool $ok, string $mensagem, int $status = 200): void
{
    if (($_POST['modal'] ?? '') !== '1') {
        return;
    }

    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        ['ok' => $ok, 'mensagem' => $mensagem],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}
```

Essa função é responsável por enviar uma resposta ao sistema quando uma requisição é feita por um modal. Ela recebe três parâmetros: `$ok`, que indica se a operação deu certo; `$mensagem`, que contém o texto da resposta; e `$status`, que define o código HTTP, sendo 200 o padrão.

O `$_POST['modal']` verifica se a requisição veio de um modal. Se não veio, o `return` encerra a função. Caso tenha vindo, `http_response_code()` define o status da resposta, `header()` informa que o conteúdo será JSON e `json_encode()` transforma os dados em JSON. Por fim, `exit` encerra a execução do código.

## 5. Verificando se o cURL está disponível

```php
function consultarViaCep($cep)
{
    if (!function_exists('curl_init')) {
        throw new RuntimeException(
            "A extensão cURL não está habilitada no PHP."
        );
    }
}
```

Essa função `consultarViaCep($cep)` serve para consultar o endereço de um CEP usando o serviço ViaCEP. O `if (!function_exists('curl_init'))` verifica se a função cURL está disponível no PHP. O sinal `!` significa negação. Caso o cURL não esteja habilitado, `throw new RuntimeException()` gera um erro informando que a extensão necessária não está ativa.

## 6. Configurando a consulta ao ViaCEP com cURL

```php
function consultarViaCep($cep)
{
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
}
```

Depois de verificar se o cURL está habilitado, o código monta a URL do ViaCEP utilizando o CEP informado. Em seguida, `curl_init($url)` inicia uma conexão com esse endereço. O `curl_setopt_array()` configura essa conexão: `CURLOPT_RETURNTRANSFER` faz com que a resposta seja armazenada em uma variável em vez de ser exibida diretamente; `CURLOPT_CONNECTTIMEOUT` define até 5 segundos para conectar; `CURLOPT_TIMEOUT` limita a requisição a 10 segundos; e `CURLOPT_SSL_VERIFYPEER` verifica o certificado de segurança do site.

## 7. Validando o token CSRF enviado pelo formulário

```php
if (
    !isset($_POST['csrf_token']) ||
    !is_string($_POST['csrf_token']) ||
    !hash_equals(
        $_SESSION['csrf_token'],
        $_POST['csrf_token']
    )
) {
    http_response_code(403);
    strainRespostaModal(
        false,
        "Solicitação inválida.",
        http_response_code() >= 400 ? http_response_code() : 422
    );
    exit("Solicitação inválida.");
}
```

Esse trecho verifica se o token CSRF enviado pelo formulário é válido. O `isset()` verifica se o token foi enviado, o `is_string()` confirma que ele é uma string e o `hash_equals()` compara o token recebido com o token salvo na sessão, verificando se são iguais. Se alguma dessas verificações falhar, o sistema retorna o código HTTP `403` (acesso proibido), envia uma mensagem de erro ao modal e encerra a execução com `exit()`.

## 8. Removendo caracteres do CEP

```php
$cepNumerico = str_replace("-", "", $cep);

$cepAnterior = preg_replace(
    '/\D/',
    '',
    (string) ($usuario['cep'] ?? '')
);
```

A primeira linha remove o hífen do CEP informado usando `str_replace()`. Na segunda, `preg_replace()` utiliza uma expressão regular para remover todos os caracteres que não sejam números do CEP anterior. O `(string)` transforma o valor em texto, e `?? ''` utiliza uma string vazia caso o CEP não exista.

**Observação:** a expressão regular correta é `/\D/`. O `\D` representa qualquer caractere que não seja um número.

## 9. Carregando os ícones Lucide

```javascript
lucide.createIcons();
```

Essa função pertence à biblioteca Lucide Icons. O `createIcons()` identifica os elementos HTML que possuem ícones Lucide e os transforma nos desenhos correspondentes em SVG.
