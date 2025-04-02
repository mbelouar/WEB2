<?php
require_once 'BD/db.php';

try {
    $sql = "PRAGMA table_info(Consumption)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Consumption Table Schema:\n";
    foreach ($columns as $column) {
        echo "{$column['name']} ({$column['type']})" . ($column['notnull'] ? ' NOT NULL' : '') . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
