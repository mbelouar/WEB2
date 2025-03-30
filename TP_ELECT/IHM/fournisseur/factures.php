<?php
session_start();
if (!isset($_SESSION['fournisseur'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Factures</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Facturation</a>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="clients.php">Clients</a></li>
        <li class="nav-item"><a class="nav-link" href="consumption.php">Consommations</a></li>
        <li class="nav-item"><a class="nav-link active" href="factures.php">Factures</a></li>
        <li class="nav-item"><a class="nav-link" href="../../traitement/fournisseurTraitement.php?action=logout">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container my-4">
  <h2 class="mb-4">Liste des Factures</h2>
  <div class="d-flex justify-content-end mb-3">
    <button class="btn btn-success">+ Créer une facture</button>
  </div>
  <div class="table-responsive shadow">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>ID Facture</th>
          <th>Client</th>
          <th>Date</th>
          <th>Montant (DH)</th>
          <th>Statut</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Dupont Jean</td>
          <td>01/02/2023</td>
          <td>620</td>
          <td><span class="badge bg-success">Payée</span></td>
          <td class="text-center">
            <button class="btn btn-sm btn-info">Voir</button>
            <button class="btn btn-sm btn-warning">Modifier</button>
            <button class="btn btn-sm btn-danger">Annuler</button>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>Martin Lucie</td>
          <td>10/02/2023</td>
          <td>500</td>
          <td><span class="badge bg-secondary">En attente</span></td>
          <td class="text-center">
            <button class="btn btn-sm btn-info">Voir</button>
            <button class="btn btn-sm btn-warning">Modifier</button>
            <button class="btn btn-sm btn-danger">Annuler</button>
          </td>
        </tr>
        <!-- Autres lignes -->
      </tbody>
    </table>
  </div>
</div>
<footer class="bg-light text-center py-3 mt-auto">
  <span>&copy; 2025 - Fournisseur d'Électricité</span>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
