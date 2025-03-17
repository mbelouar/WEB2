<!-- / Traitement/top_petition.php -->
<?php
require_once '../BD/connexion.php';

// Get the petition with the most signatures with detailed information
function getTopPetition() {
    global $pdo;
    
    $query = "SELECT p.*, COUNT(s.IDS) AS signature_count,
              MAX(s.Date) AS latest_signature_date,
              (SELECT CONCAT(s2.Prenom, ' ', s2.Nom) 
               FROM Signature s2 
               WHERE s2.IDP = p.IDP 
               ORDER BY s2.Date DESC, s2.Heure DESC 
               LIMIT 1) AS latest_signer
              FROM Petition p
              JOIN Signature s ON p.IDP = s.IDP
              GROUP BY p.IDP
              ORDER BY signature_count DESC
              LIMIT 1";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Calculate percentage of target (assuming 100 signatures is target)
        $targetSignatures = 100;
        $result['percentage'] = min(round(($result['signature_count'] / $targetSignatures) * 100), 100);
        
        // Calculate remaining days
        $endDate = new DateTime($result['DateFinP']);
        $today = new DateTime();
        $result['days_remaining'] = $today <= $endDate ? $today->diff($endDate)->days : 0;
    }
    
    return $result ?: null;
}

// Get data and return as JSON if requested via AJAX
$topPetition = getTopPetition();

if (isset($_GET['format']) && $_GET['format'] === 'json') {
    header('Content-Type: application/json');
    echo json_encode([
        'petition' => $topPetition,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// Otherwise, return HTML for direct inclusion
if ($topPetition) {
    ?>
    <div class="top-petition-details">
        <h3><?php echo htmlspecialchars($topPetition['Titre']); ?></h3>
        <div class="signature-progress">
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $topPetition['percentage']; ?>%"></div>
            </div>
            <div class="progress-stats">
                <span class="progress-count"><?php echo $topPetition['signature_count']; ?> signatures</span>
                <span class="progress-target">Objectif: 100 signatures</span>
            </div>
        </div>
        <div class="petition-stats">
            <div class="stat">
                <i class="fas fa-user-edit"></i>
                <span>Dernier signataire: <?php echo htmlspecialchars($topPetition['latest_signer']); ?></span>
            </div>
            <div class="stat">
                <i class="fas fa-calendar-day"></i>
                <span><?php echo $topPetition['days_remaining']; ?> jours restants</span>
            </div>
        </div>
        <a href="../IHM/signature.php?id=<?php echo $topPetition['IDP']; ?>" class="btn btn-primary">
            <i class="fas fa-pen-nib"></i> Signer cette pétition
        </a>
    </div>
    <?php
} else {
    ?>
    <div class="empty-top-petition">
        <p>Aucune pétition n'a encore été signée.</p>
    </div>
    <?php
}
?>
