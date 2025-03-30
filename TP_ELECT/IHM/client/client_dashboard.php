<?php
session_start();
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

require_once '../../BD/db.php';
require_once '../../BD/Facture.php';
require_once '../../BD/Consumption.php';

$client = $_SESSION['client'];
$factureModel = new Facture($pdo);
$consumptionModel = new Consumption($pdo);

// Récupérer la dernière facture
$lastInvoice = $factureModel->getLastInvoice($client['id']);
$lastInvoiceAmount = $lastInvoice ? $lastInvoice['montant'] . " DH" : "Aucune facture";
$lastInvoiceDate = $lastInvoice ? $lastInvoice['date_emission'] : "-";

// Récupérer la dernière consommation (optionnel)
$lastConsumption = $consumptionModel->getLastConsumption($client['id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Client - Espace Client</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Mon Espace</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#clientNavbar" 
            aria-controls="clientNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="clientNavbar">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="client_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="client_invoices.php">Mes Factures</a></li>
        <li class="nav-item"><a class="nav-link" href="client_new_consumption.php">Saisir Conso</a></li>
        <li class="nav-item"><a class="nav-link" href="client_complaint.php">Réclamation</a></li>
        <li class="nav-item"><a class="nav-link" href="client_notifications.php">Notifications</a></li>
        <li class="nav-item"><a class="nav-link" href="../../traitement/clientTraitement.php?action=logout">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contenu principal -->
<div class="container my-4">
  <h2 class="mb-4">Bienvenue, <?php echo htmlspecialchars($client['nom']); ?></h2>
  <div class="row g-4">
    <!-- Carte Dernière Facture -->
    <div class="col-md-4">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Dernière Facture</h5>
          <p class="display-6"><?php echo $lastInvoiceAmount; ?></p>
          <p class="mb-0"><small><?php echo $lastInvoice ? "Payée le " . $lastInvoiceDate : ""; ?></small></p>
        </div>
      </div>
    </div>
    <!-- Carte Prochaine Saisie -->
    <div class="col-md-4">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Prochaine Saisie</h5>
          <p class="mb-0">Fin du mois en cours</p>
          <a href="client_new_consumption.php" class="btn btn-primary btn-sm mt-2">Saisir maintenant</a>
        </div>
      </div>
    </div>
    <!-- Carte Réclamation -->
    <div class="col-md-4">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Faire une réclamation</h5>
          <p class="mb-0">Signalez un problème</p>
          <a href="client_complaint.php" class="btn btn-danger btn-sm mt-2">Nouvelle réclamation</a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Zone pour graphique/statistiques (à développer selon vos besoins) -->
  <div class="card shadow mt-4">
    <div class="card-body">
      <h5 class="card-title">Statistiques de Consommation</h5>
      <p class="card-text">[Graphique ou résumé mensuel]</p>
    </div>
  </div>
</div>

<!-- Pied de page -->
<footer class="bg-light text-center py-3">
  <span>&copy; 2025 - Mon Fournisseur d'Électricité</span>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
