-- backend/init.sql
CREATE DATABASE IF NOT EXISTS phptest;
USE phptest;

-- Tabela za administraciju (korisnici) – kasnije se može proširiti
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela za blog postove (ako se koristi)
CREATE TABLE IF NOT EXISTS posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  author_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id)
);

-- Tabela za termine (koje admin dodaje)
CREATE TABLE IF NOT EXISTS termini (
  id INT AUTO_INCREMENT PRIMARY KEY,
  naziv VARCHAR(100) NOT NULL,
  opis TEXT,
  datum DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela za prijave na moto trke
CREATE TABLE IF NOT EXISTS registrations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(200) NOT NULL,
  datum_rodjenja DATE NOT NULL,
  kontakt_telefon VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  motocikl_i_zapremina VARCHAR(100) NOT NULL,
  broj_vozacke_dozvole VARCHAR(50) NOT NULL,
  vozacka_dozvola_vazi_do DATE NOT NULL,
  termin_id INT NOT NULL,
  startni_broj INT NOT NULL,
  takmicarska_licenca BOOLEAN NOT NULL,
  grupa VARCHAR(50) NOT NULL,
  potvrdeno BOOLEAN DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (termin_id) REFERENCES termini(id)
);

-- Tabela za admine
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
