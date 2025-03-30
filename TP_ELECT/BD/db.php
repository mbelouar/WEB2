<?php
// BD/db.php

// Chemin vers le fichier SQLite
$dbFile = __DIR__ . '/gestion_elec_db.sqlite';

try {
    // Connexion à SQLite via PDO
    $pdo = new PDO("sqlite:" . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Activer les clés étrangères
    $pdo->exec("PRAGMA foreign_keys = ON;");

    // Création (ou mise à jour) des tables si elles n'existent pas
    $sql = "
    ----------------------------------------------------------------------------
    -- Table Fournisseur
    ----------------------------------------------------------------------------
    CREATE TABLE IF NOT EXISTS Fournisseur (
        idF INTEGER PRIMARY KEY AUTOINCREMENT,
        nomF TEXT NOT NULL,
        typeF TEXT,               
        emailF TEXT NOT NULL UNIQUE,
        passwordF TEXT NOT NULL
    );

    ----------------------------------------------------------------------------
    -- Table Agent
    ----------------------------------------------------------------------------
    CREATE TABLE IF NOT EXISTS Agent (
        idAgent INTEGER PRIMARY KEY AUTOINCREMENT,
        nomAgent TEXT NOT NULL,
        prenomAgent TEXT NOT NULL,
        emailAgent TEXT NOT NULL UNIQUE,
        passwordAgent TEXT NOT NULL,
        roleAgent TEXT NOT NULL,
        fournisseur_id INTEGER NOT NULL,
        FOREIGN KEY (fournisseur_id) REFERENCES Fournisseur(idF)
    );

    ----------------------------------------------------------------------------
    -- Table Client
    ----------------------------------------------------------------------------
    CREATE TABLE IF NOT EXISTS Client (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        cin TEXT NOT NULL,
        nom TEXT NOT NULL,
        prenom TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        telephone TEXT NOT NULL,
        adresse TEXT NOT NULL,
        password TEXT NOT NULL
    );

    ----------------------------------------------------------------------------
    -- Table Facture
    ----------------------------------------------------------------------------
    CREATE TABLE IF NOT EXISTS Facture (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        client_id INTEGER NOT NULL,
        montant REAL NOT NULL,
        date_emission TEXT NOT NULL,
        statut TEXT NOT NULL DEFAULT 'impayée',
        FOREIGN KEY (client_id) REFERENCES Client(id)
    );

    ----------------------------------------------------------------------------
    -- Table Reclamation
    ----------------------------------------------------------------------------
    CREATE TABLE IF NOT EXISTS Reclamation (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        client_id INTEGER NOT NULL,
        objet TEXT NOT NULL,
        description TEXT NOT NULL,
        photo TEXT,
        date_reclamation TEXT NOT NULL,
        statut TEXT NOT NULL DEFAULT 'en attente',
        FOREIGN KEY (client_id) REFERENCES Client(id)
    );

    ----------------------------------------------------------------------------
    -- Table Notification
    ----------------------------------------------------------------------------
    CREATE TABLE IF NOT EXISTS Notification (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        client_id INTEGER NOT NULL,
        message TEXT NOT NULL,
        date_notification TEXT NOT NULL,
        is_read INTEGER NOT NULL DEFAULT 0,
        FOREIGN KEY (client_id) REFERENCES Client(id)
    );

    ----------------------------------------------------------------------------
    -- Table Consumption (formerly ConsommationReelle)
    ----------------------------------------------------------------------------
    CREATE TABLE IF NOT EXISTS Consumption (
        idC INTEGER PRIMARY KEY AUTOINCREMENT,
        dateReleve TEXT NOT NULL,
        indexReleve INTEGER NOT NULL,
        photoCompteur TEXT,
        month TEXT NOT NULL,
        client_id INTEGER NOT NULL,
        FOREIGN KEY (client_id) REFERENCES Client(id)
    );

    ----------------------------------------------------------------------------
    -- Table ConsommationAnnuelle
    ----------------------------------------------------------------------------
    CREATE TABLE IF NOT EXISTS ConsommationAnnuelle (
        idCA INTEGER PRIMARY KEY AUTOINCREMENT,
        annee INTEGER NOT NULL,
        totalConso REAL NOT NULL,
        client_id INTEGER NOT NULL,
        FOREIGN KEY (client_id) REFERENCES Client(id)
    );
    ";

    $pdo->exec($sql);

    // (Optionnel) Supprimer l'ancienne table "Consumption" si elle existait et ne correspondait pas
    // $pdo->exec("DROP TABLE IF EXISTS OldConsumption");

    // Insertion d'un client de test s'il n'existe aucun enregistrement dans la table Client
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM Client");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['count'] == 0) {
        // Mot de passe de test : "123456"
        $hashedPassword = password_hash("123456", PASSWORD_BCRYPT);
        $insertClient = "
            INSERT INTO Client (cin, nom, prenom, email, telephone, adresse, password)
            VALUES ('CIN001', 'krichi', 'sara', 'krichi.2003.sara@gmail.com', '0610069524', 'adress1', ?)
        ";
        $stmtInsert = $pdo->prepare($insertClient);
        $stmtInsert->execute([$hashedPassword]);
    }

    // Insertion d'un fournisseur de test avec l'email "manaltalbi@gmail.com" s'il n'existe pas déjà
    $stmtF = $pdo->prepare("SELECT COUNT(*) as countF FROM Fournisseur WHERE emailF = :emailF");
    $stmtF->execute([':emailF' => "manaltalbi@gmail.com"]);
    $resultF = $stmtF->fetch(PDO::FETCH_ASSOC);

    if ($resultF['countF'] == 0) {
        $hashedPasswordF = password_hash("123456", PASSWORD_BCRYPT);
        $stmtFInsert = $pdo->prepare("
            INSERT INTO Fournisseur (nomF, typeF, emailF, passwordF)
            VALUES (:nomF, :typeF, :emailF, :passwordF)
        ");
        $nomF = "FournisseurTest";
        $typeF = "TypeExemple"; 
        $emailF = "manaltalbi@gmail.com";
        $stmtFInsert->execute([
            ':nomF' => $nomF,
            ':typeF' => $typeF,
            ':emailF' => $emailF,
            ':passwordF' => $hashedPasswordF,
        ]);
    }

    echo "Opérations effectuées avec succès.";

} catch (PDOException $e) {
    die('Erreur de connexion ou de création de tables : ' . $e->getMessage());
}
?>
