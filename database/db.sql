CREATE DATABASE banco_exemplo;

USE banco_exemplo;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE sensores(

    id int auto_increment primary key,
    tipo varchar(100) not null,
    localizacao varchar (100) not null,
    posicao varchar(100) not null

);

CREATE TABLE funcionarios(
    
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE trens(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    numero_serie VARCHAR(100) NOT NULL,
    velocidade VARCHAR(100) NOT NULL,
    estado INT NOT NULL,
    funcionario_id INT,
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id)
    
);

INSERT INTO funcionarios (nome, email, telefone, senha)
VALUES (
    'Rafael','rafael@strain.com','123456789','$2y$10$tB/pDB0hZTDJt00C2ihAm.73NNb62FNhkFEpg4BIS.iyR8Y8sFwoe'
);

































































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