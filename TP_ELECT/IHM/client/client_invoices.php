<?php
session_start();
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}
require_once '../../BD/db.php';
require_once '../../BD/Facture.php';

$factureModel = new Facture($pdo);
$clientId = $_SESSION['client']['id'];
$factures = $factureModel->getFacturesByClient($clientId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Factures - Espace Client</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
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
        <li class="nav-item"><a class="nav-link active" href="client_invoices.php">Mes Factures</a></li>
        <li class="nav-item"><a class="nav-link" href="client_new_consumption.php">Saisir Conso</a></li>
        <li class="nav-item"><a class="nav-link" href="client_complaint.php">Réclamation</a></li>
        <li class="nav-item"><a class="nav-link" href="../../traitement/clientTraitement.php?action=logout">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container my-4">
  <h2 class="mb-4">Mes Factures</h2>
  <div class="table-responsive shadow">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>Facture N°</th>
          <th>Date</th>
          <th>Montant (DH)</th>
          <th>Statut</th>
          <th>Photo compteur</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($factures)): ?>
          <?php foreach ($factures as $facture): ?>
            <tr>
              <td><?php echo $facture['id']; ?></td>
              <td><?php echo $facture['date_emission']; ?></td>
              <td><?php echo $facture['montant']; ?></td>
              <td>
                <?php if (strtolower($facture['statut']) === 'payée'): ?>
                  <span class="badge bg-success">Payée</span>
                <?php else: ?>
                  <span class="badge bg-secondary">En attente</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="#" class="text-decoration-underline" data-bs-toggle="modal" data-bs-target="#photoModal<?php echo $facture['id']; ?>">
                  Voir la photo
                </a>
                <div class="modal fade" id="photoModal<?php echo $facture['id']; ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Photo du Compteur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                      </div>
                      <div class="modal-body text-center">
                        <img src="https://via.placeholder.com/400" alt="Compteur" class="img-fluid rounded">
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td class="text-center">
                <button class="btn btn-sm btn-info">Détails</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6">Aucune facture trouvée</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<footer class="bg-light text-center py-3">
  <span>&copy; 2025 - Mon Fournisseur d'Électricité</span>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
