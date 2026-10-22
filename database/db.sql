CREATE DATABASE IF NOT EXISTS banco_exemplo;

USE banco_exemplo;

-- TABELA CLIENTES
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- TABELA SENSORES
CREATE TABLE IF NOT EXISTS sensores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(100) NOT NULL,
    localizacao VARCHAR(100) NOT NULL,
    posicao VARCHAR(100) NOT NULL
);

-- TABELA FUNCIONARIOS
CREATE TABLE IF NOT EXISTS funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- TABELA TRENS
CREATE TABLE IF NOT EXISTS trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_linha VARCHAR(100) NOT NULL,
    numero_serie VARCHAR(100) NOT NULL,
    velocidade INT NOT NULL,
    estado VARCHAR(20) NOT NULL,
    funcionario_id INT,
    FOREIGN KEY (funcionario_id)
        REFERENCES funcionarios(id)
);

-- TABELA ROTAS
CREATE TABLE IF NOT EXISTS rotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_rota VARCHAR(100) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    distancia DECIMAL(6,2) NOT NULL,
    tempo_estimado INT NOT NULL,
    data_criacao DATE NOT NULL,
    trem_id INT,
    FOREIGN KEY (trem_id)
        REFERENCES trens(id)
);

ALTER TABLE trens MODIFY estado VARCHAR(20) NOT NULL;

UPDATE trens SET estado = 'Ativo' WHERE estado = '1';
UPDATE trens SET estado = 'Inativo'
 WHERE estado NOT IN ('Ativo', 'Inativo', 'Manutencao');


INSERT INTO funcionarios (nome, email, telefone, senha)
SELECT
    'Rafael',
    'rafael@strain.com',
    '123456789',
    '$2y$10$tB/pDB0hZTDJt00C2ihAm.73NNb62FNhkFEpg4BIS.iyR8Y8sFwoe'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM funcionarios WHERE email = 'rafael@strain.com'
);

INSERT INTO sensores (id, tipo, localizacao, posicao) VALUES
(1, 'temperatura', 'vagão 1', 'ativo'),
(2, 'vibração', 'vagão 2', 'inativo'),
(3, 'pressão', 'vagão 3', 'ativo'),
(4, 'umidade', 'vagão 3', 'manutenção'),
(5, 'temperatura', 'vagão 2', 'ativo');

INSERT INTO clientes (nome, email, telefone, senha)
VALUES
('Lucas Almeida', 'lucas.almeida@email.com', '987654321', 'lucas123'),
('Mariana Costa', 'mariana.costa@email.com', '912345678', 'mariana123'),
('Pedro Santos', 'pedro.santos@email.com', '998765432', 'pedro123'),
('Ana Oliveira', 'ana.oliveira@email.com', '923456789', 'ana123'),
('Gabriel Souza', 'gabriel.souza@email.com', '934567812', 'gabriel123'),
('Julia Ferreira', 'julia.ferreira@email.com', '945678123', 'julia123'),
('Matheus Rodrigues', 'matheus.rodrigues@email.com', '956781234', 'matheus123'),
('Beatriz Lima', 'beatriz.lima@email.com', '967812345', 'beatriz123'),
('Felipe Martins', 'felipe.martins@email.com', '978123456', 'felipe123'),
('Camila Ribeiro', 'camila.ribeiro@email.com', '989123456', 'camila123'),
('Rafael Mendes', 'rafael.mendes@email.com', '921876543', 'rafael123'),
('Isabela Carvalho', 'isabela.carvalho@email.com', '932187654', 'isabela123'),
('Bruno Gomes', 'bruno.gomes@email.com', '943218765', 'bruno123'),
('Larissa Lopes', 'larissa.lopes@email.com', '954321876', 'larissa123'),
('Thiago Barbosa', 'thiago.barbosa@email.com', '965432187', 'thiago123'),
('Amanda Nunes', 'amanda.nunes@email.com', '976543218', 'amanda123'),
('Vinicius Rocha', 'vinicius.rocha@email.com', '987123654', 'vinicius123'),
('Leticia Dias', 'leticia.dias@email.com', '918273645', 'leticia123'),
('Gustavo Teixeira', 'gustavo.teixeira@email.com', '929384756', 'gustavo123'),
('Nicole Moreira', 'nicole.moreira@email.com', '938475612', 'nicole123');

INSERT INTO funcionarios (nome, email, telefone, senha)
VALUES
('Carlos Silva', 'carlos.silva@email.com', '912345678', 'carlos123'),
('Maria Oliveira', 'maria.oliveira@email.com', '923456789', 'maria123'),
('João Santos', 'joao.santos@email.com', '934567812', 'joao123'),
('Ana Costa', 'ana.costa@email.com', '945678123', 'ana123'),
('Pedro Almeida', 'pedro.almeida@email.com', '956781234', 'pedro123');
('Lucas Ferreira', 'lucas.ferreira@email.com', '967812345', 'lucas123'),
('Juliana Martins', 'juliana.martins@email.com', '978123456', 'juliana123'),
('Rafael Rodrigues', 'rafael.rodrigues@email.com', '989234567', 'rafael123'),
('Beatriz Gomes', 'beatriz.gomes@email.com', '991345678', 'beatriz123'),
('Gabriel Ribeiro', 'gabriel.ribeiro@email.com', '992456789', 'gabriel123'),
('Mariana Carvalho', 'mariana.carvalho@email.com', '993567812', 'mariana123'),
('Felipe Lopes', 'felipe.lopes@email.com', '994678123', 'felipe123'),
('Camila Soares', 'camila.soares@email.com', '995781234', 'camila123'),
('Bruno Fernandes', 'bruno.fernandes@email.com', '996812345', 'bruno123'),
('Larissa Pereira', 'larissa.pereira@email.com', '997123456', 'larissa123'),
('Thiago Barbosa', 'thiago.barbosa@email.com', '998234567', 'thiago123'),
('Amanda Nunes', 'amanda.nunes@email.com', '999345678', 'amanda123'),
('Gustavo Lima', 'gustavo.lima@email.com', '910456789', 'gustavo123'),
('Isabela Rocha', 'isabela.rocha@email.com', '911567812', 'isabela123'),
('Matheus Dias', 'matheus.dias@email.com', '912678123', 'matheus123'),
('Leticia Monteiro', 'leticia.monteiro@email.com', '913781234', 'leticia123'),
('Vinicius Cardoso', 'vinicius.cardoso@email.com', '914812345', 'vinicius123'),
('Fernanda Teixeira', 'fernanda.teixeira@email.com', '915123456', 'fernanda123'),
('Diego Moreira', 'diego.moreira@email.com', '916234567', 'diego123'),
('Patricia Mendes', 'patricia.mendes@email.com', '917345678', 'patricia123'),
('Leonardo Castro', 'leonardo.castro@email.com', '918456789', 'leonardo123'),
('Sofia Araujo', 'sofia.araujo@email.com', '919567812', 'sofia123'),
('Rodrigo Correia', 'rodrigo.correia@email.com', '920678123', 'rodrigo123'),
('Claudia Vieira', 'claudia.vieira@email.com', '921781234', 'claudia123'),
('Eduardo Batista', 'eduardo.batista@email.com', '922812345', 'eduardo123');

