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

-- Select statement to view the data from the Studente table
select * from RE.Studente;
select * from Re.Credenziali;
-- drop table RE.Credenziali;
-- drop table Re.Studente;
