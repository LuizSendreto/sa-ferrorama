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
    'Rafael','rafael@strain.com','123456789','$2y$10$tB/pDB0hZTDJt00C2ihAm.73NNb62FNhkFEpg4BIS.iyR8Y8sFwoe'
);

CREATE TABLE trens(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_linha VARCHAR(100) NOT NULL,
    numero_serie VARCHAR(100) NOT NULL,
    velocidade VARCHAR(100) NOT NULL,
    estado INT NOT NULL,
    funcionario_id INT,
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id)
    
);

Create table rotas(
    id int auto_increment primary key,
    nome_linha varchar(100) not null,
    descricao varchar(100) not null,
    distancia varchar(100) not null,
    tempo_estimado varchar(100) not null,
    data_criacao date not null,
    trem_id int,
    foreign key (trem_id) references trens(id)


);