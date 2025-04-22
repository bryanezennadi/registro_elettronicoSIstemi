-- Creating the RE database (commented out if you want to avoid re-creation)
-- drop database RE;
create database RE;

-- Using the RE database
use RE;

-- Table for storing credentials
create table RE.Credenziali(
    username varchar(100) unique,
    password varchar(100),
    id_credenziale int PRIMARY KEY auto_increment
);

-- Table for storing teachers (Docenti)
create table RE.Docente(
    nome varchar(30),
    cognome varchar(40),
    data_nascita DATE NOT NULL,
    residenza VARCHAR(255),
    codice_fiscale VARCHAR(16) UNIQUE NOT NULL,
    id_credenziale int,
    foreign key (id_credenziale) references RE.Credenziali(id_credenziale),
    id_docente int PRIMARY KEY AUTO_INCREMENT
);

-- Table for storing staff (Personale)
create table RE.Personale(
    nome varchar(30),
    cognome varchar(40),
    data_nascita DATE NOT NULL,
    residenza VARCHAR(255),
    codice_fiscale VARCHAR(16) UNIQUE NOT NULL,
    id_credenziale int,
    foreign key (id_credenziale) references RE.Credenziali(id_credenziale),
    id_personale int PRIMARY KEY AUTO_INCREMENT
);

-- Table for storing parents (Genitori)
create table RE.Genitore(
    nome varchar(30),
    cognome varchar(40),
    data_nascita DATE NOT NULL,
    residenza VARCHAR(255),
    codice_fiscale VARCHAR(16) UNIQUE NOT NULL,
    id_credenziale int,
    foreign key (id_credenziale) references RE.Credenziali(id_credenziale),
    id_genitore int PRIMARY KEY AUTO_INCREMENT
);

-- Table for storing students (Studenti)
create table RE.Studente(
    nome varchar(30),
    cognome varchar(40),
    id_genitore int,
    data_nascita DATE NOT NULL,
    residenza VARCHAR(255),
    codice_fiscale VARCHAR(16) UNIQUE NOT NULL,
    id_studente int PRIMARY KEY AUTO_INCREMENT,
    id_credenziale int,
    foreign key (id_credenziale) references RE.Credenziali(id_credenziale),
    foreign key (id_genitore) references RE.Genitore(id_genitore)
);
create table RE.Classe(
nome char(2),
id int primary key,
articolazione varchar(10),
indirizzo varchar(10)
);
CREATE TABLE RE.classe_docente (
    id_docente INT,
    id_classe INT,
    PRIMARY KEY (id_docente, id_classe),
    FOREIGN KEY (id_docente) REFERENCES RE.Docente(id_docente),
    FOREIGN KEY (id_classe) REFERENCES RE.Classe(id)
);


-- Select statement to view the data from the Studente table
select * from RE.Studente;
select * from Re.Credenziali;
-- Inserisci i docenti
INSERT INTO RE.Docente (nome, cognome, data_nascita, residenza, codice_fiscale, id_credenziale) VALUES
('Marco', 'Rossi', '1980-04-12', 'Via Roma 10, Milano', 'RSSMRC80D12F205X', 1),
('Laura', 'Bianchi', '1975-09-03', 'Via Dante 22, Firenze', 'BNCLRA75P43D612Z', 2),
('Giovanni', 'Verdi', '1988-01-27', 'Piazza Garibaldi 5, Napoli', 'VRDGVN88A27F839A', 3),
('Anna', 'Neri', '1990-11-15', 'Corso Vittorio 3, Torino', 'NRAANN90S55L219T', 4);

-- Inserisci le credenziali
INSERT INTO RE.Credenziali (username, password, id_credenziale) VALUES
('m.rossi', '$2y$10$qjQvFkFuJ6A9gXqLD5oV3O7dP8E1uCI8lbfPHsMSnQiO7Qpp8vWmK', 1), -- password: docente123
('l.bianchi', '$2y$10$9qZ6dAv1emYBcFObfsWa/.5A3BWR8ePqxI3XDi4z9vAeZ2SeDOy9i',2), -- password: insegnante456
('g.verdi', '$2y$10$Ysz5aIXMszTfRlmFq4BlzOgX4OrQqAfBpGc9ogUik5FtqHFFKq1Gi',3), -- password: scuola789
('a.neri', '$2y$10$zBOzOxwDqZtW2H3A08U7Oe3giGRQFtqTgUWrX4vGuOJvYulEqyBcy',4); -- password: classe101


select * from RE.Docente;
select * from RE.Genitore;
delete from RE.credenziali where username = 'bryan__';
SELECT id_studente, nome, cognome 
        FROM RE.Studente 
        WHERE id_genitore = 11395802;
-- drop table RE.Credenziali;
-- drop table Re.Studente;
