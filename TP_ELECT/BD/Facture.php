<?php
// BD/Facture.php

class Facture {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer la liste des factures d'un client
    public function getFacturesByClient($clientId) {
        $sql = "SELECT * FROM Facture WHERE client_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer la dernière facture d'un client
    public function getLastInvoice($clientId) {
        $sql = "SELECT * FROM Facture WHERE client_id = ? ORDER BY date_emission DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer le détail d'une facture
    public function getFactureById($id) {
        $sql = "SELECT * FROM Facture WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Marquer une facture comme payée
    public function markAsPaid($id) {
        $sql = "UPDATE Facture SET statut = 'payée' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
