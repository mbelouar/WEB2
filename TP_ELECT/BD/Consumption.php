<?php
// BD/Consumption.php

class Consumption {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Enregistrer une nouvelle saisie de consommation
    public function addConsumption($clientId, $month, $currentReading, $photoPath) {
        $sql = "INSERT INTO Consumption (client_id, month, indexReleve, photoCompteur, dateReleve)
                VALUES (?, ?, ?, ?, datetime('now'))";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$clientId, $month, $currentReading, $photoPath]);
    }
    
    public function getConsumptionsByClient($clientId) {
        $sql = "SELECT idC as id, month, indexReleve as current_reading, dateReleve as date_entry 
                FROM Consumption WHERE client_id = ? ORDER BY dateReleve DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer la dernière consommation saisie
    public function getLastConsumption($clientId) {
        $sql = "SELECT idC as id, month, indexReleve as current_reading, dateReleve as date_entry 
                FROM Consumption WHERE client_id = ? ORDER BY dateReleve DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
