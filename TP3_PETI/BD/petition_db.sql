CREATE DATABASE IF NOT EXISTS petition_db;
USE petition_db;

-- Table des pétitions
CREATE TABLE Petition (
    IDP INT AUTO_INCREMENT PRIMARY KEY,
    Titre VARCHAR(255) NOT NULL,
    Description TEXT NOT NULL,
    DatePublic DATE NOT NULL,
    DateFinP DATE NOT NULL,
    PorteurP VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL
);

-- Table des signatures
CREATE TABLE Signature (
    IDS INT AUTO_INCREMENT PRIMARY KEY,
    IDP INT NOT NULL,
    Nom VARCHAR(255) NOT NULL,
    Prenom VARCHAR(255) NOT NULL,
    Pays VARCHAR(100) NOT NULL,
    Date DATE NOT NULL,
    Heure TIME NOT NULL,
    Email VARCHAR(255) NOT NULL,
    FOREIGN KEY (IDP) REFERENCES Petition(IDP) ON DELETE CASCADE
);
