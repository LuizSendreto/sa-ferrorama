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