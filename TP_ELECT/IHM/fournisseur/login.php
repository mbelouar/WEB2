<?php
session_start();
if (isset($_SESSION['fournisseur'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - Facturation Électricité</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Gestion Électricité</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarConnexion" 
            aria-controls="navbarConnexion" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarConnexion">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="../../index.php">Accueil</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container d-flex flex-column justify-content-center align-items-center vh-100">
  <div class="card shadow" style="max-width: 400px; width: 100%;">
    <div class="card-body">
      <h2 class="card-title text-center mb-4">Connexion Fournisseur</h2>
      <form action="../../traitement/fournisseurTraitement.php" method="POST">
        <input type="hidden" name="action" value="login">
        <div class="mb-3">
          <label for="username" class="form-label">Identifiant (email)</label>
          <input type="text" class="form-control" name="emailF" id="username" placeholder="Entrez votre email" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Mot de passe</label>
          <input type="password" class="form-control" name="passwordF" id="password" placeholder="Entrez votre mot de passe" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
      </form>
      <div class="text-center mt-3">
        <a href="#">Mot de passe oublié ?</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
