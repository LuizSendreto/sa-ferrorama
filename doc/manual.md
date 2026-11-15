# STRAIN — Manual de Uso

**Sistema de Monitoramento Ferroviário em Tempo Real**  
**Protótipo funcional**  
**Equipe:** Juana, Luiz, Rafael e Nicole

> **Nota sobre a documentação:** este manual foi convertido do arquivo original “Manual SA.docx”. Algumas telas são imagens de protótipo e algumas funcionalidades são descritas como requisitos previstos, não necessariamente como recursos implementados na versão final. Antes da Release `v1.0.0`, revise especialmente o cadastro de usuários, o redirecionamento após logout, os sensores em tempo real e a exportação de relatórios.

## Conteúdo

- [Introdução](#introdução)
- [Instrução de uso](#instrução-de-uso)
- [Dicas e boas práticas](#dicas-e-boas-práticas)
- [Solução de problemas — FAQ](#solução-de-problemas--faq)
- [Suporte e contatos](#suporte-e-contatos)

---

## Introdução

#### Apresentação geral
O STRAIN é um sistema ferroviário desenvolvido para receber, validar, armazenar e apresentar informações coletadas por sensores IoT instalados em locomotivas e trilhos.

Entre os dados previstos estão informações de velocidade, localização, consumo de energia e detecção de falhas. Esses dados são enviados para um banco de dados central e utilizados pelo sistema para apresentar informações de monitoramento e relatórios.

O sistema também possui funcionalidades relacionadas ao acompanhamento dos trens, localização, linhas, horários, sensores, alertas e relatórios.

Os requisitos estabelecem que os dados do dashboard devem ser atualizados em intervalos máximos de 5 segundos.

### Público-alvo
O sistema é destinado principalmente a:

- Administradores do sistema;

- Operadores ferroviários;

- Usuários responsáveis pelo acompanhamento dos trens;

- Profissionais envolvidos no monitoramento ferroviário;

- Usuários que precisam consultar horários e informações das linhas.

O acesso às funções administrativas é restrito a usuários autenticados, conforme as regras de negócio do sistema.

## Instrução de uso

#### Acesso ao sistema
Ao acessar o STRAIN, o usuário será direcionado à página inicial do sistema.

A tela apresenta as opções relacionadas ao acesso e cadastro, permitindo que o usuário entre no sistema ou realize um novo cadastro.

![Captura de tela do manual STRAIN](manual_assets/media/image2.png)

Na tela inicial também são apresentadas as linhas ferroviárias disponíveis em Joinville — SC:

- Linha azul — Sul;

- Linha verde — Centro;

- Linha amarela — Itaúm;

- Linha vermelha — Villa Nova.

![Captura de tela do manual STRAIN](manual_assets/media/image7.png)

#### Cadastro de usuário
Caso o usuário ainda não possua uma conta, deverá selecionar a opção "Cadastrar" ou "Cadastrar-se".

![Captura de tela do manual STRAIN](manual_assets/media/image11.png)

Na tela de cadastro são apresentados campos para:

- Nome de usuário;

- E-mail;

- Senha;

- Confirmação da senha;

- Permissão para compartilhamento da localização.

O mockup apresenta a opção de permitir o compartilhamento da localização durante o cadastro.

Após preencher os campos obrigatórios, o usuário deverá realizar o cadastro.

O sistema deve validar os campos obrigatórios antes de salvar os dados, impedindo cadastros incompletos.

### Login
Para acessar o sistema, o usuário deverá informar suas credenciais cadastradas.

![Captura de tela do manual STRAIN](manual_assets/media/image8.png)

#### Passo a passo
![Captura de tela do manual STRAIN](manual_assets/media/image3.png)

1. Acesse a tela de login.
2. Informe o e-mail cadastrado.
3. Informe a senha.
4. Selecione **"Entrar"**.
5. Aguarde a validação das credenciais.

- Somente usuários com e-mail e senha válidos cadastrados no banco de dados poderão acessar o sistema.

- Caso as credenciais estejam incorretas, o usuário não terá acesso às áreas restritas e será direcionado para a tela de login.

- Após realizar o login corretamente, o usuário terá acesso à tela principal do sistema.

![Captura de tela do manual STRAIN](manual_assets/media/image5.png)

A tela principal tem como objetivo apresentar as informações necessárias para o acompanhamento dos trens.

Entre as informações previstas estão:

- Localização dos trens;

- Velocidade;

- Status operacional;

- Dados dos sensores;

- Informações das linhas;

- Alertas;

- Informações de desempenho.

O sistema deve apresentar a velocidade atual de cada trem monitorado e sua localização em um mapa geográfico interativo.

### Seleção de linhas
Na tela do sistema são apresentadas quatro linhas:

- Linha azul — Sul;

- Linha verde — Centro;

- Linha amarela — Itaúm;

- Linha vermelha — Villa Nova.

![Captura de tela do manual STRAIN](manual_assets/media/image1.png)

O usuário poderá selecionar a linha desejada para consultar suas informações.

#### Consulta de horários
Ao selecionar uma linha, o usuário poderá consultar os horários dos trens disponíveis.

![Captura de tela do manual STRAIN](manual_assets/media/image4.png)

No mockup, a Linha Azul apresenta exemplos de horários distribuídos entre manhã, tarde e noite, incluindo:

6:00 — Trem 1010;

7:00 — Trem 1534;

11:30 — Trem 6521;

15:00 — Trem 4812;

18:00 — Trem 9287;

19:00 — Trem 5478;

23:00 — Trem 3604.

Os horários apresentados acima correspondem ao conteúdo do protótipo.

Uma das regras de negócio do sistema determina que os usuários devem poder visualizar os horários dos trens.

### Monitoramento dos trens
O sistema permite o acompanhamento do desempenho dos trens em tempo real.

Entre as informações previstas estão:

- Velocidade atual;

- Localização;

- Status operacional;

- Dados coletados pelos sensores;

- Informações relacionadas ao funcionamento do trem.

O status operacional deve ser apresentado de forma diferenciada visualmente, utilizando as categorias:

- **Normal**;

- **Alerta**;

- **Falha**.

Os dados do dashboard devem ser atualizados automaticamente, sem necessidade de recarregar manualmente a página.

![Captura de tela do manual STRAIN](manual_assets/media/image12.png)

### Sensores e dados
Os sensores são utilizados para coletar informações relacionadas aos trens e aos trilhos. O sistema deve receber dados em tempo real dos sensores instalados nas locomotivas e nos trilhos, validar essas informações e armazená-las para consultas futuras. Cada sensor deve estar associado a um trem específico no momento do cadastro. O sistema também deve permitir a visualização dos sensores cadastrados, apresentando informações como:

- ID;

- Trem;

- Rota;

- Localização;

- Tipo de dado monitorado.

![Captura de tela do manual STRAIN](manual_assets/media/image6.png)

### Alertas
Quando o sistema identificar uma situação de **Alerta** ou **Falha**, o evento deverá ser registrado e destacado visualmente no dashboard.

![Captura de tela do manual STRAIN](manual_assets/media/image10.png)

Os alertas têm como objetivo chamar a atenção do usuário para situações que necessitem de acompanhamento.

O sistema deve gerar alertas quando forem detectados problemas nos dados ou no funcionamento monitorado.

### Relatórios
O STRAIN possui como requisito a geração de relatórios analíticos utilizando os dados coletados pelos sensores. Os relatórios podem ser utilizados para analisar informações históricas e auxiliar na tomada de decisões. O sistema deve permitir a consulta de dados históricos e a geração de relatórios analíticos.

Para gerar um relatório, deverá ser informado pelo menos um período, contendo:

- Data inicial;

- Data final.

Também está prevista a possibilidade de exportação dos relatórios em formatos como:

- PDF;

- CSV.

### Logout
![Captura de tela do manual STRAIN](manual_assets/media/image9.png)

Para sair do sistema, o usuário deverá selecionar a opção **"Logout"**.

Após realizar o logout:

1.  A sessão autenticada será encerrada;

2.  O usuário perderá o acesso às áreas restritas;

3.  O sistema deverá redirecioná-lo para a tela de login.

Essa regra está definida nos requisitos funcionais e nas regras de negócio do sistema.

## Dicas e boas práticas
Para utilizar o TRAIN de maneira adequada, recomenda-se:

- Manter o e-mail e a senha em segurança;

- Não compartilhar credenciais de acesso;

- Conferir as informações antes de realizar operações administrativas;

- Observar os alertas apresentados no dashboard;

- Conferir a linha e o horário antes de consultar informações específicas;

- Verificar os dados apresentados pelos sensores;

- Utilizar filtros adequados ao gerar relatórios;

- Realizar logout após terminar o uso do sistema.

### Cuidados com operações administrativas
As operações de cadastro, edição e exclusão devem ser realizadas somente por usuários autorizados. O sistema determina que apenas administradores autenticados podem realizar essas operações. Além disso, exclusões devem exigir confirmação explícita do administrador.

## Solução de problemas — FAQ
### Não consigo entrar no sistema. O que devo fazer?
Verifique se o e-mail e a senha foram digitados corretamente.

O acesso é permitido somente para usuários que possuem credenciais válidas cadastradas no sistema.

### Esqueci minha senha. O que faço?
Utilize o procedimento de recuperação de acesso disponibilizado pelo sistema, caso esteja implementado na versão utilizada.

**Observação:** o procedimento específico de recuperação de senha não está detalhado nos documentos do projeto.

### O dashboard não está mostrando informações atualizadas.
Verifique a conexão com o sistema e aguarde a atualização automática dos dados.

O sistema possui como requisito a atualização dos dados do dashboard em intervalos máximos de 5 segundos.

### Por que um sensor não pode ser excluído?
Um sensor que possua registros de dados associados não poderá ser excluído.

Essa regra existe para preservar os dados históricos armazenados no sistema.

### O que significa o status "Alerta"?
O status **Alerta** indica que o sistema identificou uma situação que precisa ser acompanhada.

O evento deve ser registrado e destacado visualmente no dashboard.

### O que significa o status "Falha"?
O status **Falha** indica que foi identificada uma condição de falha no monitoramento.

O sistema deve registrar o evento e destacá-lo visualmente para o usuário.

### Posso consultar informações antigas?
Sim. O sistema possui como requisito o armazenamento dos dados coletados e a possibilidade de realizar consultas históricas.

### É possível exportar relatórios?
Sim. O sistema possui como requisito a exportação de relatórios em formatos como PDF e CSV.

## Suporte e contatos
### Equipe de desenvolvimento
**Projeto:** TRAIN — Sistema de Monitoramento Ferroviário

**Desenvolvedores:**

- Juana

- Luiz

- Rafael

- Nicole

**Local do projeto:** Joinville — Santa Catarina

### Canais de suporte
Para dúvidas, problemas ou sugestões relacionadas ao protótipo, o usuário deverá entrar em contato com a equipe responsável pelo projeto.

**E-mail:** \[suporte@strain.com\]

**Telefone:** \[+55 47 98721-0167\]

**Repositório do projeto:** \[[<u>Link do Repositório</u>](https://github.com/LuizSendreto/sa-ferrorama.git)\]

**Instituição de ensino:** \[Escola Moinho Sesi Senai de Referência\]
