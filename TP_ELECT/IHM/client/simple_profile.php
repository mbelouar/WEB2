<?php
session_start();
require_once '../../BD/db.php';
require_once '../../BD/Client.php';

// Check if the user is logged in
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

// Get client ID from session
$clientId = $_SESSION['client']['id'];

// Create Client model instance
$clientModel = new Client($pdo);

// Get client data from database
$client = $clientModel->getProfile($clientId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Client</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .profile-card {
      max-width: 600px;
      margin: 40px auto;
    }
    .profile-header {
      text-align: center;
      padding: 20px;
    }
    .profile-icon {
      width: 100px;
      height: 100px;
      background-color: #f8f9fa;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px auto;
    }
    .info-row {
      display: flex;
      border-bottom: 1px solid #eee;
      padding: 10px 0;
    }
    .info-label {
      flex-basis: 120px;
      font-weight: bold;
    }
    .info-value {
      flex-grow: 1;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card shadow profile-card">
      <div class="profile-header bg-light">
        <div class="profile-icon">
          <i class="fas fa-user-circle fa-4x text-primary"></i>
        </div>
        <h2>Profil Client</h2>
        <p>Vos informations personnelles</p>
      </div>
      
      <div class="card-body">
        <?php if ($client): ?>
          <div class="info-row">
            <div class="info-label">Nom</div>
            <div class="info-value"><?php echo htmlspecialchars($client['nom']); ?></div>
          </div>
          <div class="info-row">
            <div class="info-label">Prénom</div>
            <div class="info-value"><?php echo htmlspecialchars($client['prenom']); ?></div>
          </div>
          <div class="info-row">
            <div class="info-label">CIN</div>
            <div class="info-value"><?php echo htmlspecialchars($client['cin']); ?></div>
          </div>
          <div class="info-row">
            <div class="info-label">Email</div>
            <div class="info-value"><?php echo htmlspecialchars($client['email']); ?></div>
          </div>
          <div class="info-row">
            <div class="info-label">Téléphone</div>
            <div class="info-value"><?php echo htmlspecialchars($client['telephone']); ?></div>
          </div>
          <div class="info-row">
            <div class="info-label">Adresse</div>
            <div class="info-value"><?php echo htmlspecialchars($client['adresse']); ?></div>
          </div>
        <?php else: ?>
          <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Impossible de charger les informations du profil.
          </div>
          <div>
            <h4>Données de session</h4>
            <pre><?php print_r($_SESSION); ?></pre>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="card-footer text-center">
        <a href="client_dashboard.php" class="btn btn-primary">
          <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
        </a>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
