<?php
session_start();
if (!isset($_SESSION['fournisseur'])) {
    header("Location: login.php");
    exit;
}
require_once '../../BD/db.php';

// Si nous sommes en mode édition, récupérons les données du client à modifier
$editingClient = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM Client WHERE id = ?");
    $stmt->execute([$id]);
    $editingClient = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération de la liste de tous les clients pour affichage
$stmt = $pdo->query("SELECT * FROM Client");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Clients</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Facturation</a>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="clients.php">Clients</a></li>
        <li class="nav-item"><a class="nav-link" href="consumption.php">Consommations</a></li>
        <li class="nav-item"><a class="nav-link" href="factures.php">Factures</a></li>
        <li class="nav-item"><a class="nav-link" href="../../traitement/fournisseurTraitement.php?action=logout">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">
  <h2 class="mb-4">Gestion des Clients</h2>
  
  <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
    <!-- Formulaire pour ajouter un client -->
    <div class="card mb-4">
      <div class="card-header">Ajouter un Client</div>
      <div class="card-body">
        <form method="post" action="../../traitement/clientTraitement.php">
          <input type="hidden" name="action" value="addClient">
          <div class="mb-3">
            <label class="form-label">CIN</label>
            <input type="text" name="cin" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Adresse</label>
            <input type="text" name="adresse" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success">Ajouter</button>
          <a href="clients.php" class="btn btn-secondary">Annuler</a>
        </form>
      </div>
    </div>
  <?php elseif ($editingClient): ?>
    <!-- Formulaire pour modifier un client -->
    <div class="card mb-4">
      <div class="card-header">Modifier un Client</div>
      <div class="card-body">
        <form method="post" action="../../traitement/clientTraitement.php">
          <input type="hidden" name="action" value="editClient">
          <input type="hidden" name="id" value="<?php echo $editingClient['id']; ?>">
          <div class="mb-3">
            <label class="form-label">CIN</label>
            <input type="text" name="cin" class="form-control" value="<?php echo $editingClient['cin']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?php echo $editingClient['nom']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" value="<?php echo $editingClient['prenom']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $editingClient['email']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="telephone" class="form-control" value="<?php echo $editingClient['telephone']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Adresse</label>
            <input type="text" name="adresse" class="form-control" value="<?php echo $editingClient['adresse']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mot de passe (laisser vide pour conserver l'actuel)</label>
            <input type="password" name="password" class="form-control">
          </div>
          <button type="submit" class="btn btn-warning">Modifier</button>
          <a href="clients.php" class="btn btn-secondary">Annuler</a>
        </form>
      </div>
    </div>
  <?php else: ?>
    <div class="mb-3 text-end">
      <a href="clients.php?action=add" class="btn btn-success">+ Ajouter un client</a>
    </div>
  <?php endif; ?>

  <!-- Affichage de la liste des clients -->
  <div class="table-responsive shadow">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>CIN</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Adresse</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clients as $c): ?>
        <tr>
          <td><?php echo $c['id']; ?></td>
          <td><?php echo $c['cin']; ?></td>
          <td><?php echo $c['nom']; ?></td>
          <td><?php echo $c['prenom']; ?></td>
          <td><?php echo $c['email']; ?></td>
          <td><?php echo $c['telephone']; ?></td>
          <td><?php echo $c['adresse']; ?></td>
          <td class="text-center">
            <a href="clients.php?action=edit&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
            <a href="#" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $c['id']; ?>)">Supprimer</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<footer class="bg-light text-center py-3 mt-auto">
  <span>&copy; 2025 - Fournisseur d'Électricité</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
// 1. Confirmation de suppression stylée avec SweetAlert2
function confirmDelete(clientId) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous êtes sur le point de supprimer ce client.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#bb3d5d',
        cancelButtonColor: '#f8a9b7',
        confirmButtonText: 'OK',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "../../traitement/clientTraitement.php?action=deleteClient&id=" + clientId;
        }
    });
}

// 2. Affichage d'un message d'erreur stylé si l'email existe déjà
<?php if (isset($_GET['error']) && $_GET['error'] === 'emailUsed'): ?>
Swal.fire({
    title: 'Erreur',
    text: 'Cette adresse e-mail est déjà utilisée !',
    icon: 'error',
    confirmButtonColor: '#bb3d5d',
    confirmButtonText: 'OK'
});
<?php endif; ?>
</script>
</body>
</html>
