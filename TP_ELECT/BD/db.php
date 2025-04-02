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

    // Vérifier si une reconstruction de la table Consumption est nécessaire
    $recreateConsumption = false;
    try {
        // Récupérer la structure actuelle de la table Consumption
        $consumptionStructure = $pdo->query("PRAGMA table_info(Consumption)")->fetchAll(PDO::FETCH_ASSOC);
        
        // Compter combien de colonnes on a
        $expectedColumns = [
            'idC', 'client_id', 'month', 'current_reading', 
            'photo', 'dateReleve', 'status'
        ];
        
        $columnsFound = 0;
        foreach ($consumptionStructure as $column) {
            if (in_array($column['name'], $expectedColumns)) {
                $columnsFound++;
            }
        }
        
        // Si on n'a pas assez de colonnes, on reconstruit
        if ($columnsFound != count($expectedColumns)) {
            $recreateConsumption = true;
        }
    } catch (Exception $e) {
        // Si on ne peut pas récupérer la structure, la table n'existe probablement pas
        $recreateConsumption = true;
    }

    // Recréer la table Consumption si nécessaire
    if ($recreateConsumption) {
        // Sauvegarde des données existantes
        $consumptionData = [];
        try {
            $consumptionData = $pdo->query("SELECT * FROM Consumption")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Pas de données à sauvegarder
        }
        
        // Supprimer la table existante
        $pdo->exec("DROP TABLE IF EXISTS Consumption");
        
        // Créer la table avec une structure propre
        $pdo->exec("
            CREATE TABLE Consumption (
                idC INTEGER PRIMARY KEY AUTOINCREMENT,
                client_id INTEGER NOT NULL,
                month TEXT NOT NULL,
                current_reading INTEGER NOT NULL,
                photo TEXT,
                dateReleve TEXT NOT NULL DEFAULT (datetime('now')),
                status TEXT NOT NULL DEFAULT 'pending',
                FOREIGN KEY (client_id) REFERENCES Client(id)
            )
        ");
        
        // Restaurer les données si on en avait
        if (!empty($consumptionData)) {
            $stmt = $pdo->prepare("
                INSERT INTO Consumption (client_id, month, current_reading, photo, dateReleve, status)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            foreach ($consumptionData as $row) {
                $clientId = $row['client_id'] ?? null;
                $month = $row['month'] ?? $row['dateReleve'] ?? null;
                $reading = $row['current_reading'] ?? $row['indexReleve'] ?? 0;
                $photo = $row['photo'] ?? $row['photoCompteur'] ?? null;
                $date = $row['dateReleve'] ?? $row['date_entry'] ?? date('Y-m-d H:i:s');
                $status = $row['status'] ?? 'pending';
                
                if ($clientId) {
                    $stmt->execute([$clientId, $month, $reading, $photo, $date, $status]);
                }
            }
        }
    }

    // Création (ou mise à jour) des autres tables si elles n'existent pas
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
        consumption_id INTEGER,
        kwh_consumed INTEGER,
        FOREIGN KEY (client_id) REFERENCES Client(id),
        FOREIGN KEY (consumption_id) REFERENCES Consumption(idC)
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
        reponse TEXT,
        date_traitement TEXT,
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

    // Only show success message if not in an API context
    if (!defined('API_REQUEST') || !API_REQUEST) {
        // echo "Opérations effectuées avec succès.";
    }

} catch (PDOException $e) {
    die('Erreur de connexion ou de création de tables : ' . $e->getMessage());
}
?>
