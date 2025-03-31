<?php
// BD/Reclamation.php

class Reclamation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter une réclamation avec option de photo
    public function addReclamation($clientId, $objet, $description, $photo = null) {
        $sql = "INSERT INTO Reclamation (client_id, objet, description, photo, date_reclamation, statut)
                VALUES (?, ?, ?, ?, NOW(), 'en attente')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$clientId, $objet, $description, $photo]);
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
}
?>
