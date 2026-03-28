CREATE DATABASE IF NOT EXISTS fivem_gestionale;
USE fivem_gestionale;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    ruolo VARCHAR(50) NOT NULL
);

CREATE TABLE weapons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_compratore VARCHAR(50),
    cognome_compratore VARCHAR(50),
    seriale VARCHAR(50) UNIQUE,
    modello VARCHAR(50),
    tipo_utente INT,
    data_ora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    revocata TINYINT(1) DEFAULT 0
);

CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(100),
    descrizione TEXT,
    stato VARCHAR(20) DEFAULT 'aperto',
    creato_da VARCHAR(50),
    ruolo_creatore VARCHAR(50),
    tipo VARCHAR(50) -- 'investigativo', 'federale', 'interno'
);

CREATE TABLE aziende (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    titolare VARCHAR(100)
);

CREATE TABLE dipendenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    azienda_id INT,
    nome VARCHAR(100),
    ruolo VARCHAR(50),
    FOREIGN KEY (azienda_id) REFERENCES aziende(id) ON DELETE CASCADE
);

-- Utenti di test (la password è uguale all'username per semplicità in locale)
INSERT INTO users (username, password, ruolo) VALUES 
('admin1', 'admin1', 'admin'),
('arma60', 'arma60', 'armeria60'),
('cid1', 'cid1', 'cid'),
('lspd1', 'lspd1', 'lspd'),
('lssd1', 'lssd1', 'lssd'),
('procura1', 'procura1', 'procura'),
('marshall1', 'marshall1', 'marshall'),
('fib1', 'fib1', 'fib'),
('iaa1', 'iaa1', 'iaa'),
('alto_lspd', 'alto_lspd', 'alto_comando_lspd');

-- Dati fittizi
INSERT INTO weapons (nome_compratore, cognome_compratore, seriale, modello, tipo_utente) VALUES ('Mario', 'Rossi', 'AB123', 'Pistola 9mm', 60);
INSERT INTO reports (titolo, descrizione, stato, creato_da, ruolo_creatore, tipo) VALUES ('Caso 01', 'Test', 'aperto', 'lspd1', 'lspd', 'investigativo');