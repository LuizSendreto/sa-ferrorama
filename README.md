# SA_Ferrorama

## Proposta do Sistema:
Criar uma estrutura de códigos que receba, processe e exiba em tempo real os dados enviados pelos sensores IoT instalados na locomotiva e nos trilhos, permitindo o acompanhamento do desempenho do trem, a identificação de falhas e a geração de relatórios analíticos. A proposta busca facilitar a busca de informações e o acompanhamento das operações ferroviárias e dados necessários de forma mais organizada e em um único lugar.


---------------------------------------------------------------------------------------------


## Objetivos do projeto:
    O sistema procura facilitar e agilizar a busca de informações, além da gestão de: 

 * Localizações.
 * Trens.
 * Sensores.
 * Alarmes.
 * Trilhos.


---------------------------------------------------------------------------------------------


## Membros da equipe:
Luiz Felipe Sendreto dos Santos.
Juana Franciscão.
Nicole Jordan Amaral.
Rafael Tironi.

---------------------------------------------------------------------------------------------


# Funcionalidades 
As funcionalidades do site são:

## Regras de Negócio
 * O sistema deve receber dados em tempo real dos sensores instalados na locomotiva e nos trilhos.
 * O sistema deve validar os dados recebidos dos IoT.
 * O sistema deve exibir dados em tempo real em um painel (dashboard).
 * O sistema deve acabar a sessão, após logout, redirecionado para à tela de login.
 * O sistema deve gerar alertas em caso de problemas detectados.
 * O sistema deve armazenar os dados coletados.
 * O sistema deve permitir consulta de dados históricos.
 * O sistema deve gerar relatórios analíticos.
 * O sistema deve permitir o acompanhamento do desempenho do trem em tempo real.
 * O sistema deve permitir login de usuários (administrador, cliente etc ).
 * O sistema deve permitir exportação de relatórios (PDF, CSV, etc.).
 * O sistema deve disponibilizar a funcionalidade de log out,
 * O sistema deve exibir o nome do administrador autenticado na tela principal.
 * O sistema deve listar todos os sensores cadastrados com ID(trem, rotas, etc..), localização e tipo de dado monitorado.
 * O sistema deve disponibilizar o botão para visualização de detalhes de cada sensor.
 * O sistema deve disponibilizar um formulário com campos para nome do sensor,
 * localização e tipo de dado monitorado.
 * O sistema deve impedir a exclusão se o sensor possuir dados registrados.
 * O sistema exibir a mensagem: Não é possível excluir sensores com dados registrados.
 * O sistema deve exibir em tempo real a velocidade atual de cada trem monitorado.
 * O sistema deve exibir a localização do trem em mapa geográfico interativo.
 * O sistema deve exibir o status operacional de cada trem (Normal, Alerta ou Falha) com diferenciação visual.
 * O sistema deve localizar os usuários.

 ## Requisitos Funcionais
* O sistema deve receber dados em tempo real dos sensores instalados na locomotiva e nos trilhos.
* O sistema deve validar os dados recebidos dos IoT.
* O sistema deve exibir dados em tempo real em um painel (dashboard).
* O sistema deve acabar a sessão, após logout, redirecionado para à tela de login.
* O sistema deve gerar alertas em caso de problemas detectados.
* O sistema deve armazenar os dados coletados.
* O sistema deve permitir consulta de dados históricos.
* O sistema deve gerar relatórios analíticos.
* O sistema deve permitir o acompanhamento do desempenho do trem em tempo real.
* O sistema deve permitir login de usuários (administrador, cliente etc ).
* O sistema deve permitir exportação de relatórios (PDF, CSV, etc.).
* O sistema deve disponibilizar a funcionalidade de log out.
* O sistema deve exibir o nome do administrador autenticado na tela principal.
* O sistema deve listar todos os sensores cadastrados com ID(trem, rotas, etc..), localização e tipo de dado monitorado.
* O sistema deve disponibilizar o botão para visualização de detalhes de cada sensor.
* O sistema deve disponibilizar um formulário com campos para nome do sensor, localização e tipo de dado monitorado.
* O sistema deve impedir a exclusão se o sensor possuir dados registrados.
* O sistema exibir a mensagem: Não é possível excluir sensores com dados registrados.
* O sistema deve exibir em tempo real a velocidade atual de cada trem monitorado.
* O sistema deve exibir a localização do trem em mapa geográfico interativo.
* O sistema deve exibir o status operacional de cada trem (Normal, Alerta ou Falha) com diferenciação visual.
* O sistema deve localizar os usuários.

 ## Requisitos Não Funcionais
*  O sistema deve processar dados em tempo real com baixa latência.
* O sistema deve suportar grande volume de dados (alta escalabilidade).
* O sistema deve garantir segurança na comunicação (ex: criptografia).
* O sistema deve ter alta disponibilidade (mínimo de falhas).
* O sistema ter a interface deve ser intuitiva e de fácil uso.
* O sistema deve ter o tempo de resposta inferior a 2 segundos.
* O sistema deve ser compatível com diferentes dispositivos (desktop, tablet).
* O sistema deve possuir backup automático dos dados.
* O sistema deve garantir integridade e confiabilidade dos dados.
* O sistema deve seguir padrões de desenvolvimento e documentação.
* O sistema deve atualizar os dados do dashboard de monitoramento em tempo real em intervalos de no máximo 5 segundos.
* O sistema deve funcionar em no máximo 3 segundos em condições normais de rede.
* O sistema deve ter a geração de relatórios que não deve ultrapassar 10 segundos para períodos de até 30 dias de dados.
* O sistema deve suportar consultas simultâneas de múltiplos sensores sem degradação perceptível de performance.
* O sistema deve armazenar senhas utilizando algoritmo de hash seguro (bcrypt ou equivalente).
* O sistema deve realizar a comunicação entre cliente e servidor via protocolo HTTPS/TLS.
* O sistema deve implementar proteção contra ataques de SQL Injection e Cross-Site Scripting (XSS).
* O sistema deve encerrar automaticamente as sessões inativas por mais de 30 minutos.
* O sistema deve registrar log de auditoria para todas as operações críticas (login, logout, exclusões) com timestamp e ID do usuário.
* O sistema deve autenticar acesso às APIs de dados dos sensores via token (JWT ou equivalente).
* O sistema deve ter disponibilidade mínima de 99,5% ao mês (downtime máximo de 3,6 horas/mês).
* O sistema deve ser capaz de se recuperar automaticamente de falhas de conexão com os sensores IoT sem perda permanente de dados.
* O sistema deve, em caso de falha parcial, continuar operando os módulos não afetados (degradação elegante).
* O sistema deve ter uma rotina de backup automático diário com retenção mínima de 30 dias, no banco de dados.
* O sistema deve ter uma interface responsiva e adaptada para uso em monitores de desktop (resolução mínima 1280x720).
* O sistema deve ter as mensagens de erro claras, objetivas e orientadas ao usuário, sem expor detalhes técnicos internos.
* O sistema deve utilizar código de cores padronizado para indicar status (verde/amarelo/vermelho) no dashboard de monitoramento.
* O sistema deve permitir as confirmações de ações destrutivas (exclusão) que devem ser apresentadas em modal com opções claras de confirmar ou cancelar.
* O sistema deve permitir que a tela principal tenha um botão de logout visível.
* O sistema deve ter a integração com sensores IoT deve seguir protocolo padronizado (MQTT ou HTTP REST) para garantir interoperabilidade.
* O sistema deve ter um código-fonte que siga padrões de codificação documentados e boas práticas da linguagem/framework adotado.
* O sistema deve conter testes automatizados cobrindo ao menos os módulos de autenticação, cadastro e exclusão.
* O sistema deve ter a arquitetura que permita a adição de novos tipos de sensores sem necessidade de refatoração estrutural do sistema.
* O sistema deve suportar o crescimento do volume de dados históricos sem degradação das consultas de relatórios.
* O sistema deve ter a API de integração que deve ser documentada (padrão OpenAPI/Swagger) para facilitar a conexão de novos dispositivos.
* O sistema deve identificar falhas e anomalias automaticamente.
* O sistema deve validar os campos obrigatórios antes de salvar, impedindo cadastros incompletos.
* O sistema deve atualizar automaticamente os dados do dashboard semnecessidade de recarga manual da página.  


---------------------------------------------------------------------------------------------


## Metodologias:
    DevOps: Decidimos por essa metodologia porque ela permite que a equipe crie uma funcionalidade teste a mesma antes de adicionar mais coisas ao projeto, isso facilita o trabalho colaborativo, traz segurança e velocidade ao desenvolvimento.

    Kanban: O kanban é uma método de fluxo de trabalho que utiliza um quadro com colunas e cartões, optamos pela utilização dessa metodologia pois ela já está presente no git hub e permite de forma visual o acompanhamento das tarefas.


 ---------------------------------------------------------------------------------------------


## Tecnologias
  
* PHP — Desenvolvimento do sistema do backend;
* MySQL — Gerenciamento do banco de dados;
* HTML — Estrutura das páginas;
* CSS — Estilização e personalização do front-end;
* JavaScript — Interatividade e funcionalidades do sistema;
* XAMPP — Ambiente de desenvolvimento local;
* Git e GitHub — Versionamento e colaboração da equipe.

