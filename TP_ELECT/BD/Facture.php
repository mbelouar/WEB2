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
    
    // Créer une nouvelle facture basée sur une consommation
    public function createInvoiceFromConsumption($clientId, $consumption, $previousConsumption = null) {
        // Vérifier si la consommation est approuvée
        if (!isset($consumption['status']) || $consumption['status'] !== 'approved') {
            // Check if the consumption has an id field, if so use that to check the status in the database
            if (isset($consumption['id'])) {
                try {
                    $sql = "SELECT status FROM Consumption WHERE idC = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$consumption['id']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (!$result || $result['status'] !== 'approved') {
                        error_log("Cannot create invoice: consumption not approved. Status: " . ($result['status'] ?? 'unknown'));
                        return ['success' => false, 'error' => 'Consumption not approved'];
                    }
                } catch (\Exception $e) {
                    error_log("Error checking consumption status: " . $e->getMessage());
                    return ['success' => false, 'error' => 'Error checking consumption status'];
                }
            } else {
                error_log("Cannot create invoice: consumption not approved or missing status");
                return ['success' => false, 'error' => 'Consumption not approved or missing status'];
            }
        }
        
        // Calcul de la consommation en kWh
        $kwhConsumed = $consumption['current_reading'];
        
        // Si on a une lecture précédente, calculer la différence
        if ($previousConsumption && isset($previousConsumption['current_reading'])) {
            $kwhConsumed = $consumption['current_reading'] - $previousConsumption['current_reading'];
            // S'assurer que la valeur n'est pas négative (cas où le compteur a été remplacé)
            if ($kwhConsumed < 0) {
                $kwhConsumed = $consumption['current_reading'];
            }
        }
        
        // Calcul du montant selon le barème
        $montant = $this->calculateAmount($kwhConsumed);
        
        // Date d'émission (aujourd'hui)
        $dateEmission = date('Y-m-d H:i:s');
        
        // Création de la facture
        $sql = "INSERT INTO Facture (client_id, montant, date_emission, statut, consumption_id, kwh_consumed) 
                VALUES (?, ?, ?, 'impayée', ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        try {
            $result = $stmt->execute([
                $clientId, 
                $montant, 
                $dateEmission, 
                $consumption['id'], 
                $kwhConsumed
            ]);
            
            if ($result) {
                return [
                    'success' => true,
                    'invoice_id' => $this->pdo->lastInsertId(),
                    'amount' => $montant,
                    'kwh_consumed' => $kwhConsumed
                ];
            } else {
                error_log("Error creating invoice: " . print_r($stmt->errorInfo(), true));
                return ['success' => false, 'error' => 'Database error'];
            }
        } catch (\PDOException $e) {
            error_log("PDO Exception creating invoice: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // Calculer le montant de la facture selon les tranches de consommation
    private function calculateAmount($kwhConsumed) {
        // TVA: 18%
        $tva = 0.18;
        
        // Prix unitaire par tranche
        $price1 = 0.82; // 0-100 kWh
        $price2 = 0.92; // 101-150 kWh
        $price3 = 1.10; // 151+ kWh
        
        // Calcul du montant HT par tranches
        $montantHT = 0;
        
        if ($kwhConsumed <= 100) {
            $montantHT = $kwhConsumed * $price1;
        } elseif ($kwhConsumed <= 150) {
            $montantHT = (100 * $price1) + (($kwhConsumed - 100) * $price2);
        } else {
            $montantHT = (100 * $price1) + (50 * $price2) + (($kwhConsumed - 150) * $price3);
        }
        
        // Ajout de la TVA
        $montantTTC = $montantHT * (1 + $tva);
        
        // Arrondir à 2 décimales
        return round($montantTTC, 2);
    }
}
?>
