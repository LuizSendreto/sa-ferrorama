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

stmt=mysqliprepare(conexao, $sql);

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
