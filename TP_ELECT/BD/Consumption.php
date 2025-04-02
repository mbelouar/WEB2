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
                'dateReleve' => $row['dateReleve'] ?? null,
                'status' => $row['status'] ?? 'pending'
            ];
        } catch (\PDOException $e) {
            error_log("Error in getLastConsumption: " . $e->getMessage());
            return null;
        }
    }
    
    // Récupérer la dernière consommation d'un client
    public function getLastConsumptionByClient($clientId) {
        return $this->getLastConsumption($clientId);
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
            'dateReleve' => $row['dateReleve'] ?? null,
            'status' => $row['status'] ?? 'pending'
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
    
    // Update the status of a consumption entry
    public function updateStatus($id, $status) {
        try {
            if (!$id) {
                error_log("Cannot update status: missing consumption ID");
                return false;
            }
            
            // Validate status
            if (!in_array($status, ['pending', 'approved', 'rejected'])) {
                error_log("Invalid status: $status");
                return false;
            }
            
            $sql = "UPDATE Consumption SET status = ? WHERE idC = ?";
            $stmt = $this->pdo->prepare($sql);
            
            if (!$stmt) {
                error_log("SQL Prepare Error: " . print_r($this->pdo->errorInfo(), true));
                return false;
            }
            
            $result = $stmt->execute([$status, $id]);
            
            if (!$result) {
                error_log("SQL Execute Error: " . print_r($stmt->errorInfo(), true));
                return false;
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("PDO Error in updateStatus: " . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            error_log("General Error in updateStatus: " . $e->getMessage());
            return false;
        }
    }
    
    // Get consumptions by status
    public function getConsumptionsByStatus($status) {
        try {
            // Join with Client table to get client information
            $sql = "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom 
                    FROM Consumption c
                    JOIN Client cl ON c.client_id = cl.id
                    WHERE c.status = ?
                    ORDER BY c.dateReleve DESC";
            
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                error_log("SQL Prepare Error: " . print_r($this->pdo->errorInfo(), true));
                return [];
            }
            
            $stmt->execute([$status]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getConsumptionsByStatus: " . $e->getMessage());
            return [];
        }
    }
    
    // Get all consumptions
    public function getAllConsumptions() {
        try {
            // Join with Client table to get client information
            $sql = "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom 
                    FROM Consumption c
                    JOIN Client cl ON c.client_id = cl.id
                    ORDER BY c.dateReleve DESC";
            
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                error_log("SQL Prepare Error: " . print_r($this->pdo->errorInfo(), true));
                return [];
            }
            
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error in getAllConsumptions: " . $e->getMessage());
            return [];
        }
    }
    
    // Get the previous consumption for a client
    public function getPreviousConsumption($clientId, $currentConsumptionId) {
        try {
            error_log("Getting previous consumption for client $clientId before consumption $currentConsumptionId");
            
            // Get the current consumption to find its date
            $currentConsumption = $this->getConsumptionById($currentConsumptionId);
            
            if (!$currentConsumption) {
                error_log("Cannot find current consumption with ID: $currentConsumptionId");
                return null;
            }
            
            // Get the previous consumption based on date
            $sql = "SELECT * FROM Consumption 
                    WHERE client_id = ? 
                    AND idC != ? 
                    AND dateReleve < (SELECT dateReleve FROM Consumption WHERE idC = ?)
                    ORDER BY dateReleve DESC, idC DESC
                    LIMIT 1";
            
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                error_log("SQL Prepare Error: " . print_r($this->pdo->errorInfo(), true));
                return null;
            }
            
            $stmt->execute([$clientId, $currentConsumptionId, $currentConsumptionId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$row) {
                error_log("No previous consumption found for client $clientId before consumption $currentConsumptionId");
                return null;
            }
            
            error_log("Found previous consumption: " . print_r($row, true));
            return $this->formatConsumptionData($row);
        } catch (\PDOException $e) {
            error_log("Error in getPreviousConsumption: " . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            error_log("General Error in getPreviousConsumption: " . $e->getMessage());
            return null;
        }
    }
    
    // Debugging function to check table schema
    public function checkTableSchema() {
        try {
            $sql = "PRAGMA table_info(Consumption)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error checking table schema: " . $e->getMessage());
            return null;
        }
    }
}
?>
