-- Création de la base de données
CREATE DATABASE IF NOT EXISTS petition_db;
USE petition_db;

-- Table des pétitions
CREATE TABLE Petition (
    idPetition INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    datePublic DATE NOT NULL,
    dateFin DATE NOT NULL,
    personneID INT NOT NULL
);

-- Table des signatures
CREATE TABLE Signature (
    idSignature INT PRIMARY KEY AUTO_INCREMENT,
    idPetition INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    pays VARCHAR(100) NOT NULL,
    dateSignature TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idPetition) REFERENCES Petition(idPetition)
);

-- Index pour optimiser les recherches
CREATE INDEX idx_petition_dates ON Petition(datePublic, dateFin);
CREATE INDEX idx_signature_petition ON Signature(idPetition); 