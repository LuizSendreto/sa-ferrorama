
CREATE DATABASE IF NOT EXISTS banco_exemplo;

USE banco_exemplo;

-- ==========================================
-- TABELA USUARIOS
-- ==========================================

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('cliente', 'funcionario') NOT NULL,
    nivel_acesso ENUM('comum', 'admin') NOT NULL DEFAULT 'comum'
);

-- ==========================================
-- TABELA SENSORES
-- ==========================================

CREATE TABLE IF NOT EXISTS sensores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(100) NOT NULL,
    localizacao VARCHAR(100) NOT NULL,
    posicao VARCHAR(100) NOT NULL
);

-- ==========================================
-- TABELA TRENS
-- ==========================================

CREATE TABLE IF NOT EXISTS trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_linha VARCHAR(100) NOT NULL,
    numero_serie VARCHAR(100) NOT NULL,
    velocidade INT NOT NULL,
    estado VARCHAR(20) NOT NULL,
    funcionario_id INT,
    CONSTRAINT fk_trens_usuarios
        FOREIGN KEY (funcionario_id) REFERENCES usuarios(id)
);

-- ==========================================
-- TABELA ROTAS
-- ==========================================

CREATE TABLE IF NOT EXISTS rotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_rota VARCHAR(100) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    distancia DECIMAL(6,2) NOT NULL,
    tempo_estimado INT NOT NULL,
    data_criacao DATE NOT NULL,
    trem_id INT,
    CONSTRAINT fk_rotas_trens
        FOREIGN KEY (trem_id) REFERENCES trens(id)
);

-- ==========================================
-- CORRECAO DOS STATUS DOS TRENS
-- ==========================================

UPDATE trens
SET estado = 'Ativo'
WHERE estado = '1';

UPDATE trens
SET estado = 'Inativo'
WHERE estado NOT IN ('Ativo', 'Inativo', 'Manutencao');

-- ==========================================
-- SENSORES DE EXEMPLO
-- ==========================================

INSERT INTO sensores (id, tipo, localizacao, posicao)
VALUES
(1, 'temperatura', 'vagão 1', 'ativo'),
(2, 'vibração', 'vagão 2', 'inativo'),
(3, 'pressão', 'vagão 3', 'ativo'),
(4, 'umidade', 'vagão 3', 'manutenção'),
(5, 'temperatura', 'vagão 2', 'ativo');

-- ==========================================
-- USUARIOS DE EXEMPLO
-- SENHAS PROTEGIDAS COM HASH BCRYPT
-- ==========================================

INSERT INTO usuarios
(id, nome, email, telefone, senha, tipo, nivel_acesso)
VALUES
-- ADMINISTRADOR
(1, 'Rafael', 'rafael@strain.com', '123456789', '$2y$10$tB/pDB0hZTDJt00C2ihAm.73NNb62FNhkFEpg4BIS.iyR8Y8sFwoe', 'funcionario', 'admin'),

-- CLIENTES
(2, 'Lucas Almeida', 'lucas.almeida@email.com', '987654321', '$2y$10$a.i0AWM9T8B9W491OsOGwuK/MqCYz7WbqS3ddODuE/SY7ZAZk7EjO', 'cliente', 'comum'),
(3, 'Mariana Costa', 'mariana.costa@email.com', '912345678', '$2y$10$QYQjR.2DOhcHYyoawNxEFOJWx3daB1gxpTCii7qgVW7Z5SGdDKY6G', 'cliente', 'comum'),
(4, 'Pedro Santos', 'pedro.santos@email.com', '998765432', '$2y$10$KwuPa9DYJK/IOmnwtk5C6OKTBrBmotbKGNO7Q41qgUZNlFC8JBArm', 'cliente', 'comum'),
(5, 'Ana Oliveira', 'ana.oliveira@email.com', '923456789', '$2y$10$eilAiUdqYrzZI9Uhg2zjCu9QJ1Jl1jFDdX.qoJahHqHN.7XQdCWMu', 'cliente', 'comum'),
(6, 'Gabriel Souza', 'gabriel.souza@email.com', '934567812', '$2y$10$jn/mT/xTMFaly1fHwt4HWekELaef5mFwlzAAYbMdrGzHrhIDMBls.', 'cliente', 'comum'),
(7, 'Julia Ferreira', 'julia.ferreira@email.com', '945678123', '$2y$10$P41LrsA/i1MKUEGJUPJ/Ku2UrlwhkfphFTuK17clJRF8jKZWdpCN2', 'cliente', 'comum'),
(8, 'Matheus Rodrigues', 'matheus.rodrigues@email.com', '956781234', '$2y$10$DYrZhuJB4Vj.RMmcxTW6dObH.lH0qilQb3PPUeLM/fFkzpJMXgfCK', 'cliente', 'comum'),
(9, 'Beatriz Lima', 'beatriz.lima@email.com', '967812345', '$2y$10$sdG8cUk22yAYHo48G6qXg.76E.VlkiDPJM6c8QPo/Eeu7kGEPQca2', 'cliente', 'comum'),
(10, 'Felipe Martins', 'felipe.martins@email.com', '978123456', '$2y$10$U/9GDOCreX5NNUusBboYhezs/Sn5vAgV5ZTInl.Sot4FpCxditLXO', 'cliente', 'comum'),
(11, 'Camila Ribeiro', 'camila.ribeiro@email.com', '989123456', '$2y$10$PTr7GyALwy.o5HLj4PENNuC.RG9bvnfeVslERSmRaSJm1we9S7YIS', 'cliente', 'comum'),
(12, 'Rafael Mendes', 'rafael.mendes@email.com', '921876543', '$2y$10$MCJA5LWW5UuRbYEnWevmtuR4uden4uQiM8GiRqqCPadgC30Q91vk.', 'cliente', 'comum'),
(13, 'Isabela Carvalho', 'isabela.carvalho@email.com', '932187654', '$2y$10$PdOWhPtRT1I2dO3/UNNTMe1.Pq/Lc.1hAObrjSFiRGl5zqomQrvoa', 'cliente', 'comum'),
(14, 'Bruno Gomes', 'bruno.gomes@email.com', '943218765', '$2y$10$tHthNP.bSvBQN8/jR3vm4uDWCUrz6AcHZm32QoRfZOVWApnQYz2H6', 'cliente', 'comum'),
(15, 'Larissa Lopes', 'larissa.lopes@email.com', '954321876', '$2y$10$kz/q0AmF/UGosSRT8lOKqukcLv2JGHVtKrZHB0ABiIlS0Wvcyol1C', 'cliente', 'comum'),
(16, 'Thiago Barbosa', 'thiago.barbosa@email.com', '965432187', '$2y$10$jp8h2DoU0H9yo8DeNnxl6uT3tR0.qE37JpNLZG/upM.FiDu9NTWH2', 'cliente', 'comum'),
(17, 'Amanda Nunes', 'amanda.nunes@email.com', '976543218', '$2y$10$WKvF34J/Cdvm.Xv6lDqNleTKW.PLn2wWkTrjj4k1wwIR849ZdK84.', 'cliente', 'comum'),
(18, 'Vinicius Rocha', 'vinicius.rocha@email.com', '987123654', '$2y$10$Sh0gHUz17bGIdy4REF7vP.SZ94KedavfQ8q9b2wyFSvCshj74G.ya', 'cliente', 'comum'),
(19, 'Leticia Dias', 'leticia.dias@email.com', '918273645', '$2y$10$eS4G4GwmbU/cqScgzaGlk.GnNvKeW3Fm5XZl3eumNJCa3E7eI0KkG', 'cliente', 'comum'),
(20, 'Gustavo Teixeira', 'gustavo.teixeira@email.com', '929384756', '$2y$10$nESoZjpRirvWHlRos/x2..2vDF00lVzEPzFQUWyx9LNrgts74tdfG', 'cliente', 'comum'),
(21, 'Nicole Moreira', 'nicole.moreira@email.com', '938475612', '$2y$10$H7WT4tOjGSGTdEr.e3TPJeKRQQO6dFjPsy96DPTtLHJFZWF9Iuv8e', 'cliente', 'comum'),

-- FUNCIONARIOS
(22, 'Carlos Silva', 'carlos.silva@email.com', '912345678', '$2y$10$B9pYbi0QZFT64TTIJBXDLOPQc3hHciLp2FdW.fquPd6O2SnQMbKJe', 'funcionario', 'comum'),
(23, 'Maria Oliveira', 'maria.oliveira@email.com', '923456789', '$2y$10$CTBFmhj0nCtINufcpMTQp.iv6m707zPU3m7toxgenDAN94ith3nwC', 'funcionario', 'comum'),
(24, 'João Santos', 'joao.santos@email.com', '934567812', '$2y$10$uoU06ubSvVk4x1hmGBWDOez/zSz1H0cKwlizycLk5CvyysKnJMxKC', 'funcionario', 'comum'),
(25, 'Ana Costa', 'ana.costa@email.com', '945678123', '$2y$10$mUgDmeRY.2DGiD2xDtKD3ufFO.DHQA19Ge.Uvj4KQCHIvwjIg2wLm', 'funcionario', 'comum'),
(26, 'Pedro Almeida', 'pedro.almeida@email.com', '956781234', '$2y$10$xLUJ/U4DXqu8tq7Sf9NMO.uu4JTl3zf.gP3axiRQvdIj85uQ.qiNa', 'funcionario', 'comum'),
(27, 'Lucas Ferreira', 'lucas.ferreira@email.com', '967812345', '$2y$10$.DVF2VXNibZvLUTSGI0R6OUlHHFy8EeswGBp/t/PsOuRNcQZKPArC', 'funcionario', 'comum'),
(28, 'Juliana Martins', 'juliana.martins@email.com', '978123456', '$2y$10$e.ILpkasYfHAYNj8VNhfh.iGSpZqUCFDd7G/6WG3XjCX5H1NOPqM.', 'funcionario', 'comum'),
(29, 'Rafael Rodrigues', 'rafael.rodrigues@email.com', '989234567', '$2y$10$z58yAwpRPdDainQH1ftPe.WcRHDOxx3.GzhmyxaAjUuHuenwwd0Ny', 'funcionario', 'comum'),
(30, 'Beatriz Gomes', 'beatriz.gomes@email.com', '991345678', '$2y$10$c7EQmq6oAbDuiKGTxaoqCOCBAL4IhuKT5AaM1JCc4lisuhsMx./eq', 'funcionario', 'comum'),
(31, 'Gabriel Ribeiro', 'gabriel.ribeiro@email.com', '992456789', '$2y$10$LAEnyjNyWXWRSnlEJXXjwOhBlRlAwutrvIp0lnsRu/KPAXgv5mGd6', 'funcionario', 'comum'),
(32, 'Mariana Carvalho', 'mariana.carvalho@email.com', '993567812', '$2y$10$VSG/Vr6a./L71AnE1jBLcOIkwxeJkskwY7FBVvQb7N/vT14ikHpxS', 'funcionario', 'comum'),
(33, 'Felipe Lopes', 'felipe.lopes@email.com', '994678123', '$2y$10$tRxaARrHo.Uw6/5TaWbwD.bE5hXrZEh6y9kAFhxsFfoww0FD7TCVy', 'funcionario', 'comum'),
(34, 'Camila Soares', 'camila.soares@email.com', '995781234', '$2y$10$I37VCDn4rtJaOVEirFdbpOxYHl3r67gjjTtiYl2/VybCj/rTts2dy', 'funcionario', 'comum'),
(35, 'Bruno Fernandes', 'bruno.fernandes@email.com', '996812345', '$2y$10$GpyDA10wESD6D8n20e7KgOFoHQAcCuAtLaYdDAvY349k15Xu9VT5C', 'funcionario', 'comum'),
(36, 'Larissa Pereira', 'larissa.pereira@email.com', '997123456', '$2y$10$39dq4ecZ5AFIF71scnghkuUsdHX4yrMVPlRc402dWcI.szcmx6vSy', 'funcionario', 'comum'),
(37, 'Thiago Barbosa', 'thiago.barbosa.funcionario@email.com', '998234567', '$2y$10$myrtx1FjfRBkq7iKw1rxhOWeKp/ADPJ95l.9RSRLCA.ocsGIFBCDS', 'funcionario', 'comum'),
(38, 'Amanda Nunes', 'amanda.nunes.funcionario@email.com', '999345678', '$2y$10$86Vs9h/KychzeW6qIOUuM.gQNIoUNcEz33x.LDuooLT1.qIVtLS8y', 'funcionario', 'comum'),
(39, 'Gustavo Lima', 'gustavo.lima@email.com', '910456789', '$2y$10$GXYj/1nhAJbgFPgxMkUIMe2NhrHmCHxuOcg.w4KNVs22JCKPpK5jG', 'funcionario', 'comum'),
(40, 'Isabela Rocha', 'isabela.rocha@email.com', '911567812', '$2y$10$csiCr9OJgQRBiVAiQAdSHuSh26b0uyQ0lSqnkSolYBaLbGU5Cpboe', 'funcionario', 'comum'),
(41, 'Matheus Dias', 'matheus.dias@email.com', '912678123', '$2y$10$Qki83e2Rj9/zqTjFtmDReef4OYPnfKFIjgN4qEV8aTNornZq.L2ie', 'funcionario', 'comum'),
(42, 'Leticia Monteiro', 'leticia.monteiro@email.com', '913781234', '$2y$10$x8Au2kE.cX4LccHgW0zfyO0fZl4.R2prflGuvlgqBpg1hOZiFg1Eu', 'funcionario', 'comum'),
(43, 'Vinicius Cardoso', 'vinicius.cardoso@email.com', '914812345', '$2y$10$iN3wVapt7SLkRVFSU34af.GIxiwz3mDZHwX71JSSjFT9z3ZG.4F7i', 'funcionario', 'comum'),
(44, 'Fernanda Teixeira', 'fernanda.teixeira@email.com', '915123456', '$2y$10$27doiXY9xy/7tTbIpnIrPe9jAyCEiMUjB6R9ZHrUknZWfY8StPJ4q', 'funcionario', 'comum'),
(45, 'Diego Moreira', 'diego.moreira@email.com', '916234567', '$2y$10$kFRjF11nXhDNYiJta8nHQuM/2QcHoOW5JB7BxV5sp5Alw3PcKQgRq', 'funcionario', 'comum'),
(46, 'Patricia Mendes', 'patricia.mendes@email.com', '917345678', '$2y$10$NuGY/KcZ2Wav.tbos/xtZOjUOmi3ZVM66DlaHJsACM7Rles6EygiK', 'funcionario', 'comum'),
(47, 'Leonardo Castro', 'leonardo.castro@email.com', '918456789', '$2y$10$4TD5rgEovIHX0bTtKGTeQO5RZhgDr2a2UyUJOh9smitXxSdcCheWG', 'funcionario', 'comum'),
(48, 'Sofia Araujo', 'sofia.araujo@email.com', '919567812', '$2y$10$7gge72zfME05r2SdJVHt4O8GB4ZfNH4GK.m5E.b3/VPomWkpR5TjK', 'funcionario', 'comum'),
(49, 'Rodrigo Correia', 'rodrigo.correia@email.com', '920678123', '$2y$10$CVhdyRa9CakX4VB6M3ldY.9v9NxquyFbCDzNQMhUAPuGQj6ITtgM.', 'funcionario', 'comum'),
(50, 'Claudia Vieira', 'claudia.vieira@email.com', '921781234', '$2y$10$KWoUvZ0KHxRUx4GDnsI1VepZCJM/skXii1fqnV1LUSrD5ShsB4H/y', 'funcionario', 'comum'),
(51, 'Eduardo Batista', 'eduardo.batista@email.com', '922812345', '$2y$10$yFbAWs8bIvEjdeez6ftsZOLl6X0RrfqCUvnZOtK7FXzJTY1dXFoNu', 'funcionario', 'comum');
