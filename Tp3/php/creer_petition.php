<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

// Vérifier si les données requises sont présentes
if (empty($_POST['titre']) || empty($_POST['description']) || empty($_POST['dateFin'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

try {
    $conn = getConnection();
    
    $query = "INSERT INTO Petition (titre, description, datePublic, dateFin, personneID) 
              VALUES (?, ?, CURRENT_DATE, ?, 1)";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([
        $_POST['titre'],
        $_POST['description'],
        $_POST['dateFin']
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Pétition créée avec succès',
        'idPetition' => $conn->lastInsertId()
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la création de la pétition: ' . $e->getMessage()
    ]);
} 