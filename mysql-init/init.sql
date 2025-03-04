-- Kreiranje tabele termini (događaja/termina)
DROP TABLE IF EXISTS termini;
CREATE TABLE IF NOT EXISTS termini (
  id INT AUTO_INCREMENT PRIMARY KEY,
  naziv VARCHAR(255) NOT NULL,
  datum DATETIME NOT NULL,
  iznos VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kreiranje tabele registrations
DROP TABLE IF EXISTS registrations;
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
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  potvrdeno BOOLEAN DEFAULT 0
);

-- Kreiraj admina automatski (za test)
DROP TABLE IF EXISTS admins;
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  reset_token VARCHAR(100) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO admins (name, username, email, password)
VALUES ('Milos Admin', 'admin', 'admin@example.com', '1234');
