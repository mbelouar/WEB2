<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

// Vérifier si les données requises sont présentes
if (empty($_POST['petitionId']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['pays'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

try {
    $conn = getConnection();
    
    // Vérifier si la pétition existe et n'est pas expirée
    $queryVerif = "SELECT idPetition FROM Petition WHERE idPetition = ? AND dateFin >= CURRENT_DATE";
    $stmtVerif = $conn->prepare($queryVerif);
    $stmtVerif->execute([$_POST['petitionId']]);
    
    if (!$stmtVerif->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Pétition invalide ou expirée']);
        exit;
    }
    
    // Ajouter la signature
    $query = "INSERT INTO Signature (idPetition, nom, prenom, pays) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([
        $_POST['petitionId'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['pays']
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Signature ajoutée avec succès'
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de l\'ajout de la signature: ' . $e->getMessage()
    ]);
} 