<?php
require_once '../BD/petition.php';

// Get the most recent petitions
$recentLimit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$lastId = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

// Modified function to get recent petitions only
function getRecentPetitions($lastId, $limit) {
    global $pdo;
    $query = "SELECT p.*, 
             (SELECT COUNT(*) FROM Signature s WHERE s.IDP = p.IDP) AS nb_signatures
             FROM Petition p 
             WHERE p.IDP > :lastId 
             ORDER BY p.IDP DESC 
             LIMIT :limit";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':lastId', $lastId, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$recentPetitions = getRecentPetitions($lastId, $recentLimit);

// Return as JSON
header('Content-Type: application/json');
echo json_encode([
    'petitions' => $recentPetitions,
    'timestamp' => date('Y-m-d H:i:s')
]);
?> 