<?php
session_start();
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

require_once '../../BD/db.php';
require_once '../../BD/Notification.php';

$notificationModel = new Notification($pdo);
$clientId = $_SESSION['client']['id'];

// Récupérer les notifications du client
$notifications = $notificationModel->getNotificationsByClient($clientId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Notifications - Espace Client</title>
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
        <li class="nav-item"><a class="nav-link" href="client_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="client_invoices.php">Mes Factures</a></li>
        <li class="nav-item"><a class="nav-link" href="client_new_consumption.php">Saisir Conso</a></li>
        <li class="nav-item"><a class="nav-link" href="client_complaint.php">Réclamation</a></li>
        <li class="nav-item"><a class="nav-link active" href="client_notifications.php">Notifications</a></li>
        <li class="nav-item"><a class="nav-link" href="../../traitement/clientTraitement.php?action=logout">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">
  <h2 class="mb-4">Mes Notifications</h2>
  <div class="table-responsive shadow">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Message</th>
          <th>Date</th>
          <th>État</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($notifications)): ?>
          <?php foreach ($notifications as $notification): ?>
            <tr>
              <td><?php echo $notification['id']; ?></td>
              <td><?php echo htmlspecialchars($notification['message']); ?></td>
              <td><?php echo $notification['date_notification']; ?></td>
              <td>
                <?php echo $notification['is_read'] ? '<span class="badge bg-success">Lu</span>' : '<span class="badge bg-secondary">Non lu</span>'; ?>
              </td>
              <td>
                <?php if (!$notification['is_read']): ?>
                  <form action="../../traitement/notificationTraitement.php?action=markAsRead" method="POST" style="display:inline;">
                    <input type="hidden" name="notif_id" value="<?php echo $notification['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-primary">Marquer comme lu</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">Aucune notification trouvée</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
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