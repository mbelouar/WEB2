<?php
require_once '../BD/connexion.php';

// Get petition ID from request
$idp = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get total signature count
function getSignatureCount($idp) {
    global $pdo;
    
    $query = "SELECT COUNT(*) as total FROM Signature WHERE IDP = :idp";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idp', $idp, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? intval($result['total']) : 0;
}

// Get count and return as JSON
$totalCount = getSignatureCount($idp);

header('Content-Type: application/json');
echo json_encode([
    'count' => $totalCount,
    'timestamp' => date('Y-m-d H:i:s')
]);
?> 