<?php
require_once '../BD/connexion.php';

// Get petition ID from request
$idp = isset($_GET['id']) ? intval($_GET['id']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
$lastId = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

// Get recent signatures
function getRecentSignatures($idp, $lastId, $limit) {
    global $pdo;
    
    $query = "SELECT * FROM Signature 
             WHERE IDP = :idp AND IDS > :lastId
             ORDER BY Date DESC, Heure DESC
             LIMIT :limit";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idp', $idp, PDO::PARAM_INT);
    $stmt->bindParam(':lastId', $lastId, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$signatures = getRecentSignatures($idp, $lastId, $limit);

// Return as JSON for AJAX requests
if (isset($_GET['format']) && $_GET['format'] === 'json') {
    header('Content-Type: application/json');
    echo json_encode([
        'signatures' => $signatures,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// Otherwise return HTML
if (!empty($signatures)) {
    foreach ($signatures as $sig) {
        ?>
        <div class="signature-item fade-in" data-id="<?php echo $sig['IDS']; ?>">
            <div class="avatar">
                <?php echo strtoupper(substr($sig['Prenom'], 0, 1)); ?>
            </div>
            <div class="signature-info">
                <div class="signature-name"><?php echo htmlspecialchars($sig['Prenom'] . ' ' . $sig['Nom']); ?></div>
                <div class="signature-details">
                    <span class="signature-country"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($sig['Pays']); ?></span>
                    <span class="signature-date"><i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($sig['Date'] . ' ' . $sig['Heure'])); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<div class="empty-signatures fade-in">Aucune signature pour le moment. Soyez le premier à signer !</div>';
}
?> 