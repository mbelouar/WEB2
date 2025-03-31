<?php
// BD/Consumption.php

class Consumption {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Enregistrer une nouvelle saisie de consommation
    public function addConsumption($clientId, $month, $currentReading, $photoPath) {
        try {
            // Simplify the query to match our simplified database schema
            $sql = "INSERT INTO Consumption 
                    (client_id, month, current_reading, photo) 
                    VALUES 
                    (?, ?, ?, ?)";
            
            $stmt = $this->pdo->prepare($sql);
            
            // Print the prepared SQL for debugging
            error_log("Prepared SQL: " . $sql);
            error_log("Parameters: client_id=$clientId, month=$month, current_reading=$currentReading, photo=$photoPath");
            
            // Check if statement preparation was successful
            if (!$stmt) {
                error_log("SQL Prepare Error: " . print_r($this->pdo->errorInfo(), true));
                return false;
            }
            
            $result = $stmt->execute([
                $clientId, 
                $month, 
                $currentReading, 
                $photoPath
            ]);
            
            if (!$result) {
                error_log("SQL Execute Error: " . print_r($stmt->errorInfo(), true));
                return false;
            }
            
            // Get and immediately return the last inserted consumption
            try {
                $lastId = $this->pdo->lastInsertId();
                error_log("Last inserted ID: " . $lastId);
                
                if ($lastId) {
                    $sql = "SELECT * FROM Consumption WHERE idC = ?";
        $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$lastId]);
                    $lastRow = $stmt->fetch(\PDO::FETCH_ASSOC);
                    
                    if ($lastRow) {
                        error_log("Successfully retrieved last consumption directly after insert");
                        // Return true to indicate success, we can get the data with getLastConsumption
                        return true;
                    }
                }
            } catch (\Exception $e) {
                error_log("Error checking last inserted consumption: " . $e->getMessage());
                // Continue and return true anyway since the insertion succeeded
            }
            
            return $result;
        } catch (\PDOException $e) {
            // Log the error for debugging
            error_log("PDO Error in addConsumption: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            return false;
        } catch (\Exception $e) {
            error_log("General Error in addConsumption: " . $e->getMessage());
            return false;
        }
    }
    
    public function getConsumptionsByClient($clientId) {
        try {
            // Simple query that matches our database schema
        $sql = "SELECT * FROM Consumption WHERE client_id = ? ORDER BY dateReleve DESC";
            
        $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                error_log("SQL Prepare Error: " . print_r($this->pdo->errorInfo(), true));
                return [];
            }
            
        $stmt->execute([$clientId]);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Transform column names for easier use in the client
            return array_map(function($row) {
                // Always ensure these keys exist even if null
                return [
                    'id' => $row['idC'] ?? null,
                    'client_id' => $row['client_id'] ?? null,
                    'month' => $row['month'] ?? null,
                    'current_reading' => $row['current_reading'] ?? null,
                    'photo' => $row['photo'] ?? null,
                    'dateReleve' => $row['dateReleve'] ?? null
                ];
            }, $results);
        } catch (\PDOException $e) {
            error_log("Error in getConsumptionsByClient: " . $e->getMessage());
            return [];
        }
    }
    
    // Récupérer la dernière consommation saisie
    public function getLastConsumption($clientId) {
        try {
            // Simple query that matches our database schema
            $sql = "SELECT * FROM Consumption WHERE client_id = ? ORDER BY dateReleve DESC, idC DESC LIMIT 1";
            
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                error_log("SQL Prepare Error: " . print_r($this->pdo->errorInfo(), true));
                return null;
            }
            
            $stmt->execute([$clientId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$row) {
                return null;
            }
            
            // Transform the row to ensure consistent field names
            return [
                'id' => $row['idC'] ?? null,
                'client_id' => $row['client_id'] ?? null,
                'month' => $row['month'] ?? null,
                'current_reading' => $row['current_reading'] ?? null,
                'photo' => $row['photo'] ?? null,
                'dateReleve' => $row['dateReleve'] ?? null
            ];
        } catch (\PDOException $e) {
            error_log("Error in getLastConsumption: " . $e->getMessage());
            return null;
        }
    }
    
    // Get consumption by client ID and month
    public function getConsumptionByClientAndMonth($clientId, $month) {
        try {
            error_log("Looking for consumption with client_id=$clientId, month=$month");
            
            // Normalize the month format for comparison (just in case)
            $normalizedMonth = trim($month);
            
            // Try with exact match only
            $sql = "SELECT * FROM Consumption WHERE client_id = ? AND month = ? ORDER BY dateReleve DESC LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$clientId, $normalizedMonth]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($row) {
                error_log("Found consumption with exact match");
                return $this->formatConsumptionData($row);
            }
            
            error_log("No consumption found for client $clientId and month $normalizedMonth");
            return null;
        } catch (\PDOException $e) {
            error_log("Error in getConsumptionByClientAndMonth: " . $e->getMessage());
            return null;
        }
    }
    
    // Helper method to format consumption data consistently
    private function formatConsumptionData($row) {
        if (!$row) return null;
        
        return [
            'id' => $row['idC'] ?? null,
            'client_id' => $row['client_id'] ?? null,
            'month' => $row['month'] ?? null,
            'current_reading' => $row['current_reading'] ?? null,
            'photo' => $row['photo'] ?? null,
            'dateReleve' => $row['dateReleve'] ?? null
        ];
    }
    
    // Get consumption by ID
    public function getConsumptionById($id) {
        try {
            if (!$id) return null;
            
            $sql = "SELECT * FROM Consumption WHERE idC = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$row) {
                return null;
            }
            
            return $this->formatConsumptionData($row);
        } catch (\PDOException $e) {
            error_log("Error in getConsumptionById: " . $e->getMessage());
            return null;
        }
    }
    
    // Debugging function to check table schema
    public function checkTableSchema() {
        try {
            $result = $this->pdo->query("PRAGMA table_info(Consumption)")->fetchAll(\PDO::FETCH_ASSOC);
            error_log("Consumption table schema: " . print_r($result, true));
            return $result;
        } catch (\Exception $e) {
            error_log("Error checking table schema: " . $e->getMessage());
            return null;
        }
    }
}
?>
