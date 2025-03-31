<?php
// Debug script for invoice issues
session_start();
require_once 'BD/db.php';
require_once 'BD/Facture.php';
require_once 'BD/Consumption.php';

// Initialize models
$factureModel = new Facture($pdo);
$consumptionModel = new Consumption($pdo);

// Get client ID
$clientId = $_SESSION['client']['id'] ?? 1;

// Function to check table structure
function checkTableStructure($pdo, $tableName) {
    $result = $pdo->query("PRAGMA table_info(" . $tableName . ")");
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

// Store debug info
$debug = [];

// Check if we need to add columns to the Facture table
$columns = checkTableStructure($pdo, 'Facture');
$hasConsumptionId = false;
$hasKwhConsumed = false;

foreach ($columns as $column) {
    if ($column['name'] === 'consumption_id') {
        $hasConsumptionId = true;
    }
    if ($column['name'] === 'kwh_consumed') {
        $hasKwhConsumed = true;
    }
}

// Fix schema if needed
if (!$hasConsumptionId || !$hasKwhConsumed) {
    $debug[] = "Schema fix needed. Adding missing columns to Facture table.";
    
    try {
        // Add missing columns using ALTER TABLE
        if (!$hasConsumptionId) {
            $pdo->exec("ALTER TABLE Facture ADD COLUMN consumption_id INTEGER REFERENCES Consumption(idC)");
            $debug[] = "- Added consumption_id column";
        }
        if (!$hasKwhConsumed) {
            $pdo->exec("ALTER TABLE Facture ADD COLUMN kwh_consumed INTEGER");
            $debug[] = "- Added kwh_consumed column";
        }
    } catch (PDOException $e) {
        $debug[] = "Error fixing schema: " . $e->getMessage();
        
        // Alternative method: recreate the table
        $debug[] = "Trying alternative fix method: recreate table";
        try {
            // Backup current data
            $stmt = $pdo->query("SELECT * FROM Facture");
            $existingFactures = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Drop and recreate table
            $pdo->exec("DROP TABLE IF EXISTS Facture");
            $pdo->exec("CREATE TABLE Facture (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                client_id INTEGER NOT NULL,
                montant REAL NOT NULL,
                date_emission TEXT NOT NULL,
                statut TEXT NOT NULL DEFAULT 'impayée',
                consumption_id INTEGER,
                kwh_consumed INTEGER,
                FOREIGN KEY (client_id) REFERENCES Client(id),
                FOREIGN KEY (consumption_id) REFERENCES Consumption(idC)
            )");
            
            // Restore data (if any)
            if (!empty($existingFactures)) {
                $debug[] = "Restoring " . count($existingFactures) . " existing records";
                
                foreach ($existingFactures as $facture) {
                    $columns = implode(", ", array_keys($facture));
                    $placeholders = rtrim(str_repeat("?, ", count($facture)), ", ");
                    
                    $sql = "INSERT INTO Facture ($columns) VALUES ($placeholders)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array_values($facture));
                }
            }
            
            $debug[] = "Table recreation complete";
        } catch (PDOException $e2) {
            $debug[] = "Error with alternative fix: " . $e2->getMessage();
        }
    }
}

// Check consumptions
$consumptions = $consumptionModel->getConsumptionsByClient($clientId);
if (empty($consumptions)) {
    $debug[] = "No consumption records found for client ID $clientId. Please add a consumption record first.";
} else {
    $debug[] = "Found " . count($consumptions) . " consumption records.";
    
    // Get factures
    $factures = $factureModel->getFacturesByClient($clientId);
    if (empty($factures)) {
        $debug[] = "No invoices found. Creating a new invoice for the most recent consumption...";
        
        // Get the latest consumption
        $latestConsumption = $consumptions[0];
        
        // Find a previous consumption for comparison
        $previousConsumption = null;
        if (count($consumptions) > 1) {
            $previousConsumption = $consumptions[1];
        }
        
        // Create an invoice
        $result = $factureModel->createInvoiceFromConsumption(
            $clientId,
            $latestConsumption,
            $previousConsumption
        );
        
        if (isset($result['success']) && $result['success']) {
            $debug[] = "✅ Successfully created a new invoice with ID: " . $result['invoice_id'];
            $debug[] = "Amount: " . $result['amount'] . " DH";
            $debug[] = "kWh consumed: " . $result['kwh_consumed'];
        } else {
            $debug[] = "❌ Failed to create invoice: " . ($result['error'] ?? "Unknown error");
            
            // Try to identify the specific issue
            try {
                $stmt = $pdo->prepare("INSERT INTO Facture (client_id, montant, date_emission, statut, consumption_id, kwh_consumed) 
                VALUES (?, ?, ?, 'impayée', ?, ?)");
                
                $amount = 100; // Sample amount
                $date = date('Y-m-d H:i:s');
                $consumptionId = $latestConsumption['id'] ?? $latestConsumption['idC'] ?? null;
                $kwhConsumed = 50; // Sample kWh
                
                $debug[] = "Attempting direct insert with: client_id=$clientId, amount=$amount, date=$date, consumption_id=$consumptionId, kwh=$kwhConsumed";
                
                $success = $stmt->execute([
                    $clientId,
                    $amount,
                    $date,
                    $consumptionId,
                    $kwhConsumed
                ]);
                
                if ($success) {
                    $debug[] = "✅ Direct insert succeeded. Check for ID column name mismatch in Consumption table.";
                } else {
                    $error = $stmt->errorInfo();
                    $debug[] = "❌ Direct insert failed: " . ($error[2] ?? "Unknown error");
                }
            } catch (PDOException $e) {
                $debug[] = "❌ Direct insert exception: " . $e->getMessage();
            }
        }
    } else {
        $debug[] = "Found " . count($factures) . " invoices.";
    }
}

// Output results as HTML for readability
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Debug Tool</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        h1 { color: #2c3e50; }
        .container { max-width: 800px; margin: 0 auto; }
        .debug-log { background: #f8f9fa; border-left: 4px solid #4e73df; padding: 10px 15px; margin-bottom: 10px; }
        .action-button { background: #4e73df; color: white; border: none; padding: 10px 15px; cursor: pointer; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invoice Debug Tool</h1>
        
        <h2>Debug Log</h2>
        <?php foreach ($debug as $message): ?>
            <div class="debug-log">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endforeach; ?>
        
        <h2>Table Structure</h2>
        <div class="debug-log">
            <pre><?php print_r($columns); ?></pre>
        </div>
        
        <p>
            <a href="IHM/client/client_invoices.php" class="action-button">Check Invoices</a>
            <a href="IHM/client/client_new_consumption.php" class="action-button">Add New Consumption</a>
        </p>
    </div>
</body>
</html>
