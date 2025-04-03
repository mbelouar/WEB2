<?php
// BD/Reclamation.php

class Reclamation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter une réclamation avec option de photo
    public function addReclamation($clientId, $objet, $description, $photo = null) {
        try {
            $currentDateTime = date('Y-m-d H:i:s'); // Standard date format
            
            $sql = "INSERT INTO Reclamation (client_id, objet, description, photo, date_reclamation, statut)
                    VALUES (?, ?, ?, ?, ?, 'en attente')";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$clientId, $objet, $description, $photo, $currentDateTime]);
        } catch (PDOException $e) {
            error_log("Error in addReclamation: " . $e->getMessage());
            return false;
        }
    }

    // Récupérer la dernière réclamation d'un client
    public function getLastReclamationByClient($clientId) {
        $sql = "SELECT * FROM Reclamation WHERE client_id = ? ORDER BY id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Récupérer toutes les réclamations d'un client
    public function getReclamationsByClient($clientId) {
        $sql = "SELECT * FROM Reclamation WHERE client_id = ? ORDER BY date_reclamation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les réclamations avec les détails du client
    public function getAllReclamations() {
        try {
            $sql = "SELECT r.*, c.nom as client_nom, c.prenom as client_prenom
                    FROM Reclamation r
                    JOIN Client c ON r.client_id = c.id
                    ORDER BY r.date_reclamation DESC";
            
            $stmt = $this->pdo->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $results;
            
        } catch (PDOException $e) {
            error_log("Error in getAllReclamations: " . $e->getMessage());
            return [];
        }
    }
}
?>
