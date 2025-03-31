<?php
// Define API_REQUEST constant to prevent any text output from db.php
define('API_REQUEST', true);

// Get the database file path
$dbFile = __DIR__ . '/BD/gestion_elec_db.sqlite';

// Backup the database first
$backupFile = __DIR__ . '/BD/gestion_elec_db_backup_' . date('Y-m-d_H-i-s') . '.sqlite';
if (file_exists($dbFile)) {
    copy($dbFile, $backupFile);
    echo "Database backed up to $backupFile<br>";
}

// Include the db.php file to recreate tables with the new schema
require_once __DIR__ . '/BD/db.php';

// Check if the reponse column exists in the Reclamation table
try {
    $result = $pdo->query("PRAGMA table_info(Reclamation)");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $hasReponse = false;
    $hasDateTraitement = false;
    
    foreach ($columns as $column) {
        if ($column['name'] === 'reponse') {
            $hasReponse = true;
        }
        if ($column['name'] === 'date_traitement') {
            $hasDateTraitement = true;
        }
    }
    
    if ($hasReponse && $hasDateTraitement) {
        echo "<div style='color: green; font-weight: bold;'>La table Reclamation a été mise à jour avec succès. Les colonnes 'reponse' et 'date_traitement' sont maintenant disponibles.</div>";
    } else {
        echo "<div style='color: red; font-weight: bold;'>La mise à jour a échoué. Veuillez contacter l'administrateur.</div>";
    }
    
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>Erreur lors de la vérification des colonnes: " . $e->getMessage() . "</div>";
}

echo "<br><a href='IHM/fournisseur/reclamations.php' style='display: inline-block; padding: 10px 15px; background-color: #2B6041; color: white; text-decoration: none; border-radius: 5px;'>Retour à la page des réclamations</a>";
?>
