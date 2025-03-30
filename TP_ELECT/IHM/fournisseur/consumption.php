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
  <title>Gestion des Consommations</title>
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
        <li class="nav-item"><a class="nav-link active" href="consumption.php">Consommations</a></li>
        <li class="nav-item"><a class="nav-link" href="factures.php">Factures</a></li>
        <li class="nav-item"><a class="nav-link" href="../../traitement/fournisseurTraitement.php?action=logout">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">
  <h2 class="mb-4">Gestion des Consommations</h2>
  
  <div class="d-flex justify-content-between align-items-center mb-3">
    <button class="btn btn-secondary">Importer fichier .txt</button>
  </div>

  <div class="card shadow mb-4">
    <div class="card-header bg-white">
      <h5 class="card-title mb-0">Ajouter une consommation</h5>
    </div>
    <div class="card-body">
      <form class="row g-3" action="../../traitement/consommationTraitement.php?action=add" method="POST">
        <div class="col-md-4">
          <label for="client" class="form-label">Client</label>
          <select id="client" name="client_id" class="form-select">
            <!-- Remplacer par une boucle pour afficher la liste des clients -->
            <option value="1">Dupont Jean</option>
            <option value="2">Martin Lucie</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="annee" class="form-label">Année</label>
          <input type="text" class="form-control" name="annee" id="annee" placeholder="2023">
        </div>
        <div class="col-md-4">
          <label for="kwh" class="form-label">kWh consommés</label>
          <input type="text" class="form-control" name="totalConso" id="kwh" placeholder="3000">
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Ici, le tableau des consommations (à récupérer depuis la BD) -->
  <div class="table-responsive shadow">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>ID Conso</th>
          <th>Client</th>
          <th>Année</th>
          <th>kWh</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Dupont Jean</td>
          <td>2023</td>
          <td>3000</td>
          <td class="text-center">
            <button class="btn btn-sm btn-warning">Modifier</button>
            <button class="btn btn-sm btn-danger">Supprimer</button>
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
