<?php
session_start();
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

require_once '../../BD/db.php';
require_once '../../BD/Reclamation.php';

$reclamationModel = new Reclamation($pdo);
$clientId = $_SESSION['client']['id'];

// Récupérer les réclamations du client
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réclamation - Espace Client</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
        <li class="nav-item"><a class="nav-link" href="client_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="client_invoices.php">Mes Factures</a></li>
        <li class="nav-item"><a class="nav-link" href="client_new_consumption.php">Saisir Conso</a></li>
        <li class="nav-item"><a class="nav-link active" href="client_complaint.php">Réclamation</a></li>
        <li class="nav-item"><a class="nav-link" href="../../traitement/clientTraitement.php?action=logout">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">
  <h2 class="mb-4">Nouvelle Réclamation</h2>
  
  <div class="card shadow">
    <div class="card-body">
      <form id="complaintForm" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="complaintType" class="form-label">Type de réclamation</label>
          <select id="complaintType" name="complaintType" class="form-select" required>
            <option value="fuite_externe">Fuite Externe</option>
            <option value="fuite_interne">Fuite Interne</option>
            <option value="facture">Facture</option>
            <option value="autre">Autre (à spécifier)</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="complaintDetails" class="form-label">Détails / Description</label>
          <textarea id="complaintDetails" name="complaintDetails" class="form-control" rows="4" placeholder="Décrivez votre problème..." required></textarea>
        </div>
        <div class="mb-3">
          <label for="complaintPhoto" class="form-label">Photo / Pièce jointe (optionnel)</label>
          <input type="file" class="form-control" name="complaintPhoto" id="complaintPhoto" accept="image/*">
        </div>
        <button type="submit" class="btn btn-danger">Soumettre la réclamation</button>
      </form>
    </div>
  </div>

  <h2 class="mt-5">Mes Réclamations</h2>
  <div class="table-responsive shadow">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Objet</th>
          <th>Description</th>
          <th>Date</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody id="complaintsTable">
        <?php if (!empty($reclamations)): ?>
          <?php foreach ($reclamations as $reclamation): ?>
            <tr>
              <td><?php echo $reclamation['id']; ?></td>
              <td><?php echo htmlspecialchars($reclamation['objet']); ?></td>
              <td><?php echo htmlspecialchars($reclamation['description']); ?></td>
              <td><?php echo $reclamation['date_reclamation']; ?></td>
              <td><?php echo htmlspecialchars($reclamation['statut']); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">Aucune réclamation trouvée</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Pied de page -->
<footer class="bg-light text-center py-3">
  <span>&copy; 2025 - Mon Fournisseur d'Électricité</span>
</footer>

<!-- Script pour gérer l'AJAX -->
<script>
document.getElementById('complaintForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const formData = new FormData(this);
  document.getElementById('complaintForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  // Log des données envoyées
  console.log('Données envoyées :', [...formData.entries()]);

  axios.post('../../traitement/reclamationTraitement.php?action=add', formData)
    .then(response => {
      // Log de la réponse du serveur
      console.log('Réponse du serveur :', response.data);

      if (response.data.success) {
        const tableBody = document.getElementById('complaintsTable');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td>${response.data.reclamation.id}</td>
          <td>${response.data.reclamation.objet}</td>
          <td>${response.data.reclamation.description}</td>
          <td>${response.data.reclamation.date_reclamation}</td>
          <td>${response.data.reclamation.statut}</td>
        `;
        tableBody.prepend(newRow);
        this.reset();
      } else {
        alert('Erreur : ' + response.data.message);
      }
    })
    .catch(error => {
      // Log des erreurs
      console.error('Erreur lors de la requête :', error);
    });
});

  axios.post('../../traitement/reclamationTraitement.php?action=add', formData)   
   .then(response => {
      if (response.data.success) {
        const tableBody = document.getElementById('complaintsTable');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td>${response.data.reclamation.id}</td>
          <td>${response.data.reclamation.objet}</td>
          <td>${response.data.reclamation.description}</td>
          <td>${response.data.reclamation.date_reclamation}</td>
          <td>${response.data.reclamation.statut}</td>
        `;
        tableBody.prepend(newRow);
        this.reset();
      } else {
        alert('Erreur : ' + response.data.message);
      }
    })
    .catch(error => console.error('Erreur:', error));
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>