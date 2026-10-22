# Pesquisa sobre APIs

## 1. O que é uma API e qual sua finalidade?

API (*Application Programming Interface*) No contexto de APIs, a palavra Aplicação refere-se a qualquer software com uma função distinta. A interface pode ser pensada como um contrato de serviço entre duas aplicações. Esse contrato define como as duas se comunicam usando solicitações e respostas. A documentação de suas respectivas APIs contém informações sobre como os desenvolvedores devem estruturar essas solicitações e respostas.
APIs são mecanismos que permitem que dois componentes de software se comuniquem usando um conjunto de definições e protocolos. Por exemplo, o sistema de software do instituto meteorológico contém dados meteorológicos diários. A aplicação para a previsão do tempo em seu telefone “fala” com esse sistema por meio de APIs e mostra atualizações meteorológicas diárias no telefone.

## 2. Como funciona a comunicação entre sistemas?

Um sistema envia uma requisição para uma API, que processa o pedido e retorna uma resposta, geralmente pela internet usando HTTP ou HTTPS.

## 3. O que são APIs REST?

São APIs que seguem os princípios REST e utilizam URLs e métodos HTTP para consultar, criar, atualizar e excluir informações.

## 4. Métodos HTTP

- **GET:** consulta dados.
- **POST:** cria ou envia dados.
- **PUT:** substitui um recurso.
- **PATCH:** atualiza parte de um recurso.
- **DELETE:** exclui dados.

## 5. Requisições e respostas HTTP

A requisição é o pedido enviado ao servidor. A resposta contém o resultado e um código de status, como 200 (sucesso) ou 404 (recurso não encontrado).

## 6. O que são endpoints?

São endereços específicos utilizados para acessar recursos de uma API. Exemplo: `/api/funcionarios` pode listar os funcionários.

## 7. Troca de dados em JSON

JSON é um formato leve e organizado para trocar informações entre sistemas.

Exemplo:

```json
{
  "id": 1
  
  "nome": "João"
}
```

## 8. Diferenças entre API e sistema web tradicional

Um sistema web tradicional apresenta páginas e interfaces para usuários. Uma API disponibiliza dados e funcionalidades para outras aplicações.

## 9. Como o PHP pode consumir uma API?

O PHP pode enviar requisições HTTP utilizando cURL ou `file_get_contents()` e interpretar respostas JSON com `json_decode()`.

## 10. Como uma API disponibiliza informações?

A API recebe uma requisição, processa os dados e retorna uma resposta que outros sistemas podem utilizar.

## 11. Autenticação, autorização e segurança

A autenticação verifica a identidade do usuário, enquanto a autorização define suas permissões. O uso de HTTPS, tokens e validação de dados ajuda a proteger as informações.

## 12. Exemplos de APIs em sistemas empresariais

- **Vendas:** consulta de produtos e estoque.
- **Financeiro:** processamento de pagamentos.
- **Transporte:** consulta de rotas e entregas.
- **Recursos humanos:** compartilhamento de dados de funcionários.
- **Logística:** acompanhamento de pedidos.
