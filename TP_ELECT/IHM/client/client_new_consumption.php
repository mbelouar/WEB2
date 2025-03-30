<?php
session_start();
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

require_once '../../BD/db.php';
require_once '../../BD/Consumption.php';

$consumptionModel = new Consumption($pdo);
$clientId = $_SESSION['client']['id'];

// Récupérer l'historique des consommations du client
$consumptions = $consumptionModel->getConsumptionsByClient($clientId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Saisir Conso - Espace Client</title>
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
        <li class="nav-item"><a class="nav-link active" href="client_new_consumption.php">Saisir Conso</a></li>
        <li class="nav-item"><a class="nav-link" href="client_complaint.php">Réclamation</a></li>
        <li class="nav-item"><a class="nav-link" href="../../traitement/clientTraitement.php?action=logout">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">
  <h2 class="mb-4">Saisir ma consommation mensuelle</h2>

  <!-- Formulaire de saisie -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form id="consumptionForm" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="month" class="form-label">Mois concerné</label>
          <input type="text" class="form-control" name="month" id="month" placeholder="Février 2023" required>
        </div>
        <div class="mb-3">
          <label for="currentReading" class="form-label">Nouvelle valeur du compteur (kWh)</label>
          <input type="number" class="form-control" name="currentReading" id="currentReading" placeholder="1300" required>
        </div>
        <div class="mb-3">
          <label for="meterPhoto" class="form-label">Photo du compteur</label>
          <input type="file" class="form-control" name="meterPhoto" id="meterPhoto" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </form>
    </div>
  </div>

  <!-- Historique des consommations -->
  <h2 class="mt-5">Historique des Consommations</h2>
  <div class="table-responsive shadow">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Mois</th>
          <th>Valeur compteur (kWh)</th>
          <th>Date d'entrée</th>
        </tr>
      </thead>
      <tbody id="consumptionsTable">
        <?php if (!empty($consumptions)): ?>
          <?php foreach ($consumptions as $consumption): ?>
            <tr>
              <td><?php echo $consumption['id']; ?></td>
              <td><?php echo htmlspecialchars($consumption['month']); ?></td>
              <td><?php echo $consumption['current_reading']; ?></td>
              <td><?php echo $consumption['date_entry']; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="4">Aucune consommation trouvée</td></tr>
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
document.getElementById('consumptionForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  axios.post('../../traitement/consommationTraitement.php?action=add', formData)
    .then(response => {
      if (response.data.success) {
        // Ajout réussi
        const tableBody = document.getElementById('consumptionsTable');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td>${response.data.consumption.id}</td>
          <td>${response.data.consumption.month}</td>
          <td>${response.data.consumption.current_reading}</td>
          <td>${response.data.consumption.date_entry}</td>
        `;
        
        // Si le tableau est vide (contient seulement "Aucune consommation trouvée"), le vider
        if (tableBody.innerHTML.includes("Aucune consommation trouvée")) {
          tableBody.innerHTML = '';
        }
        
        tableBody.prepend(newRow);
        this.reset();
        alert('Consommation enregistrée avec succès!');
      } else {
        // Erreur retournée par le serveur
        alert('Erreur : ' + (response.data.message || 'Une erreur est survenue'));
      }
    })
    .catch(error => {
      console.error('Erreur:', error);
      // Gestion plus complète des erreurs
      let errorMessage = 'Une erreur inattendue s\'est produite.';
      
      if (error.response) {
        // La requête a été faite et le serveur a répondu avec un code d'état
        // qui se situe en dehors de la plage de 2xx
        errorMessage = error.response.data.message || `Erreur ${error.response.status}`;
      } else if (error.request) {
        // La requête a été faite mais aucune réponse n'a été reçue
        errorMessage = 'Aucune réponse du serveur. Vérifiez votre connexion.';
      } else {
        // Une erreur s'est produite lors de la configuration de la requête
        errorMessage = error.message;
      }
      
      alert('Erreur: ' + errorMessage);
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>