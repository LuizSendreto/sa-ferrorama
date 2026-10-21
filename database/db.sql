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
    nome_linha VARCHAR(100) NOT NULL,
    numero_serie VARCHAR(100) NOT NULL,
    velocidade VARCHAR(100) NOT NULL,
    estado INT NOT NULL,
    funcionario_id INT,
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id)
    
);

INSERT INTO trens (nome_linha, numero_serie, velocidade, estado, funcionario_id)
VALUES (
    'Linha Azul(Sul)', '1010', '80', 1, 1
);

Create table rotas(
    id int auto_increment primary key,
    nome_rota varchar(100) not null,
    descricao varchar(100) not null,
    distancia varchar(100) not null,
    tempo_estimado varchar(100) not null,
    data_criacao date not null,
    trem_id int,
    foreign key (trem_id) references trens(id)


);

Insert into rotas (nome_rota,descricao,distancia,tempo_estimado,data_criacao,trem_id)
Values('Linha Azul',
        'Rota entre o Terminal Sul e o Terminal Central',
        '12,5 km',
        '25 minutos',
        '2026-10-21',
        NULL
);

INSERT INTO funcionarios (nome, email, telefone, senha)
VALUES (
    'Rafael','rafael@strain.com','123456789','$2y$10$tB/pDB0hZTDJt00C2ihAm.73NNb62FNhkFEpg4BIS.iyR8Y8sFwoe'
);