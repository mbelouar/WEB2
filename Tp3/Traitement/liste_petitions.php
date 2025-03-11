<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $conn = getConnection();
    
    // Récupérer toutes les pétitions avec leur nombre de signatures
    $query = "
        SELECT 
            p.*,
            COUNT(DISTINCT s.idSignature) as nombreSignatures
        FROM Petition p
        LEFT JOIN Signature s ON p.idPetition = s.idPetition
        GROUP BY p.idPetition
        ORDER BY p.datePublic DESC
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $petitions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Pour chaque pétition, récupérer les 5 dernières signatures
    foreach ($petitions as &$petition) {
        $querySignatures = "
            SELECT nom, prenom, pays, dateSignature
            FROM Signature
            WHERE idPetition = ?
            ORDER BY dateSignature DESC
            LIMIT 5
        ";
        
        $stmtSignatures = $conn->prepare($querySignatures);
        $stmtSignatures->execute([$petition['idPetition']]);
        $petition['dernieresSignatures'] = $stmtSignatures->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo json_encode($petitions);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 