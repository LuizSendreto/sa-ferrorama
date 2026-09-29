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

INSERT INTO funcionarios (nome, email, telefone, senha)
VALUES (
    'Rafael',
    'rafael@strain.com',
    '123456789',
    '$2y$10$tB/pDB0hZTDJt00C2ihAm.73NNb62FNhkFEpg4BIS.iyR8Y8sFwoe'
);