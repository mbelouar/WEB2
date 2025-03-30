<?php
session_start();
if (!isset($_SESSION['fournisseur'])) {
    header("Location: login.php");
    exit;
}
require_once '../../BD/db.php';

// Récupérer le nombre exact de clients depuis la base de données
$stmt = $pdo->query("SELECT COUNT(*) AS nbClients FROM Client");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$nbClients = $result['nbClients'];

// Vous pouvez également récupérer d'autres statistiques si nécessaire
$nbImpayees = 20; 
$consoTotale = 15000; 
$revenus = 85000;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Facturation Électricité</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <!-- ... la même navbar que dans la maquette ... -->
  <div class="collapse navbar-collapse" id="navbarContent">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
      <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="clients.php">Clients</a></li>
      <li class="nav-item"><a class="nav-link" href="consumption.php">Consommations</a></li>
      <li class="nav-item"><a class="nav-link" href="factures.php">Factures</a></li>
      <li class="nav-item"><a class="nav-link" href="../../traitement/fournisseurTraitement.php?action=logout">Déconnexion</a></li>
    </ul>
  </div>
</nav>

<div class="container my-4">
  <h2 class="mb-4">Tableau de bord</h2>
  <div class="row g-4">
    <div class="col-md-3">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Clients</h5>
          <p class="display-6"><?php echo $nbClients; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Factures impayées</h5>
          <p class="display-6"><?php echo $nbImpayees; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Conso totale (kWh)</h5>
          <p class="display-6"><?php echo $consoTotale; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Revenus (DH)</h5>
          <p class="display-6"><?php echo $revenus; ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow mt-4">
    <div class="card-body">
      <h5 class="card-title">Graphiques & Statistiques</h5>
      <p class="card-text">Intégrer un graphique ici...</p>
    </div>
  </div>
</div>

<footer class="bg-light text-center py-3 mt-auto">
  <span>&copy; 2025 - Fournisseur d'Électricité</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
