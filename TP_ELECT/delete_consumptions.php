<?php
// delete_consumptions.php
session_start();
require_once 'BD/db.php';

// Define this as an API request to suppress unnecessary output
define('API_REQUEST', true);

// Add basic security - only allow POST requests
$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    // Show the form
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Supprimer les consommations</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h3>Suppression des consommations</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> 
                                <strong>Attention:</strong> Cette action est irréversible. Toutes les consommations sélectionnées seront supprimées définitivement.
                            </div>
                            
                            <form method="POST" action="">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="delete_scope" id="delete_mine" value="mine" checked>
                                    <label class="form-check-label" for="delete_mine">
                                        Supprimer uniquement <strong>mes</strong> consommations
                                    </label>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="radio" name="delete_scope" id="delete_all" value="all">
                                    <label class="form-check-label" for="delete_all">
                                        Supprimer <strong>toutes</strong> les consommations dans la base de données
                                    </label>
                                </div>
                                
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="confirm" name="confirm" required>
                                    <label class="form-check-label" for="confirm">
                                        Je confirme vouloir supprimer les données de consommation
                                    </label>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt me-2"></i> Supprimer les consommations
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">Annuler</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// We get here only if it's a POST request
$deleteScope = $_POST['delete_scope'] ?? 'mine';
$confirmed = isset($_POST['confirm']) && $_POST['confirm'] === 'on';

// Safety check - must be confirmed
if (!$confirmed) {
    die("Vous devez confirmer la suppression en cochant la case correspondante.");
}

// Check if the user is logged in as a client
$clientLoggedIn = isset($_SESSION['client']);
$clientId = $clientLoggedIn ? $_SESSION['client']['id'] : null;

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'records_deleted' => 0
];

try {
    if ($deleteScope === 'mine' && $clientLoggedIn) {
        // Delete only the current client's consumption records
        $sql = "DELETE FROM Consumption WHERE client_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$clientId]);
        $recordsDeleted = $stmt->rowCount();
        
        $response['success'] = true;
        $response['message'] = "Toutes vos consommations ont été supprimées avec succès ($recordsDeleted enregistrement(s) supprimé(s)).";
        $response['records_deleted'] = $recordsDeleted;
    } elseif ($deleteScope === 'all') {
        // Delete all consumption records
        $sql = "DELETE FROM Consumption";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $recordsDeleted = $stmt->rowCount();
        
        $response['success'] = true;
        $response['message'] = "Toutes les consommations ont été supprimées avec succès ($recordsDeleted enregistrement(s) supprimé(s)).";
        $response['records_deleted'] = $recordsDeleted;
    } else {
        $response['message'] = "Vous devez être connecté pour supprimer vos consommations.";
    }
} catch (PDOException $e) {
    $response['message'] = "Erreur lors de la suppression: " . $e->getMessage();
}

// If it's an AJAX request, return JSON
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Otherwise, show a simple HTML response
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de la suppression</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-<?= $response['success'] ? 'success' : 'danger' ?> text-white">
                        Résultat de la suppression
                    </div>
                    <div class="card-body">
                        <p class="lead"><?= $response['message'] ?></p>
                        <div class="d-grid gap-2">
                            <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
