<?php
session_start();

// Define API_REQUEST constant to prevent any text output from db.php in AJAX calls
define('API_REQUEST', true);

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

// Page title
$pageTitle = "Gestion des Clients";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle; ?> - Gestion d'Électricité</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../assets/css/fournisseur-style.css">
</head>
<body>

<!-- Page Loader -->
<div class="page-loader">
  <img src="../../uploads/Lydec.png" alt="Lydec" class="loader-logo">
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
      <img src="../../uploads/Lydec.png" alt="Lydec" class="logo">
      <div class="brand-text">
        <span class="brand-name">Lydec</span>
        <small class="brand-tagline d-none d-sm-inline">Électricité & Eau</small>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">
            <i class="fas fa-home me-1"></i> Tableau de bord
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="clients.php">
            <i class="fas fa-users me-1"></i> Clients
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="consumption.php">
            <i class="fas fa-tachometer-alt me-1"></i> Consommations
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reclamations.php">
            <i class="fas fa-comment-alt me-1"></i> Réclamations
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../traitement/fournisseurTraitement.php?action=logout">
            <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container my-4">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" data-aos="fade-right">
      <i class="fas fa-users me-2 text-primary"></i>
      <?php echo $pageTitle; ?>
    </h1>
    
    <?php if (!isset($_GET['action']) && !$editingClient): ?>
    <div data-aos="fade-left">
      <a href="clients.php?action=add" class="btn btn-accent">
        <i class="fas fa-plus-circle me-1"></i> Ajouter un client
      </a>
    </div>
    <?php endif; ?>
  </div>
  
  <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
    <!-- Formulaire pour ajouter un client -->
    <div class="card shadow mb-4" data-aos="fade-up">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-white">
          <i class="fas fa-user-plus me-2"></i> Ajouter un Client
        </h6>
      </div>
      <div class="card-body">
        <form method="post" action="../../traitement/clientTraitement.php">
          <input type="hidden" name="action" value="addClient">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">CIN</label>
              <input type="text" name="cin" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nom</label>
              <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Prénom</label>
              <input type="text" name="prenom" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Téléphone</label>
              <input type="text" name="telephone" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Adresse</label>
              <input type="text" name="adresse" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Mot de passe</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-12 mt-4">
              <button type="submit" class="btn btn-accent">
                <i class="fas fa-save me-1"></i> Ajouter
              </button>
              <a href="clients.php" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i> Annuler
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  <?php elseif ($editingClient): ?>
    <!-- Formulaire pour modifier un client -->
    <div class="card shadow mb-4" data-aos="fade-up">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-white">
          <i class="fas fa-user-edit me-2"></i> Modifier un Client
        </h6>
      </div>
      <div class="card-body">
        <form method="post" action="../../traitement/clientTraitement.php">
          <input type="hidden" name="action" value="editClient">
          <input type="hidden" name="id" value="<?php echo $editingClient['id']; ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">CIN</label>
              <input type="text" name="cin" class="form-control" value="<?php echo htmlspecialchars($editingClient['cin']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nom</label>
              <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($editingClient['nom']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Prénom</label>
              <input type="text" name="prenom" class="form-control" value="<?php echo htmlspecialchars($editingClient['prenom']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($editingClient['email']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Téléphone</label>
              <input type="text" name="telephone" class="form-control" value="<?php echo htmlspecialchars($editingClient['telephone']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Adresse</label>
              <input type="text" name="adresse" class="form-control" value="<?php echo htmlspecialchars($editingClient['adresse']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Mot de passe (laisser vide pour conserver l'actuel)</label>
              <input type="password" name="password" class="form-control">
            </div>
            <div class="col-12 mt-4">
              <button type="submit" class="btn btn-warning">
                <i class="fas fa-save me-1"></i> Enregistrer les modifications
              </button>
              <a href="clients.php" class="btn btn-secondary">
                <i class="fas fa-undo me-1"></i> Annuler
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <!-- Affichage de la liste des clients -->
  <div class="card shadow mb-4" data-aos="fade-up" data-aos-delay="<?php echo (isset($_GET['action']) || $editingClient) ? '100' : '0'; ?>">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-white">
        <i class="fas fa-list me-2"></i> Liste des Clients
      </h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>CIN</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Email</th>
              <th>Téléphone</th>
              <th>Adresse</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($clients)): ?>
              <?php foreach ($clients as $c): ?>
              <tr>
                <td><?php echo $c['id']; ?></td>
                <td><?php echo htmlspecialchars($c['cin']); ?></td>
                <td><?php echo htmlspecialchars($c['nom']); ?></td>
                <td><?php echo htmlspecialchars($c['prenom']); ?></td>
                <td><?php echo htmlspecialchars($c['email']); ?></td>
                <td><?php echo htmlspecialchars($c['telephone']); ?></td>
                <td><?php echo htmlspecialchars($c['adresse']); ?></td>
                <td>
                  <a href="clients.php?action=edit&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                  </a>
                  <button class="btn btn-sm btn-danger delete-client" onclick="confirmDelete(<?php echo $c['id']; ?>)">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8" class="text-center py-3">Aucun client trouvé</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4 mb-md-0">
        <h5>Lydec</h5>
        <p class="mb-3">Votre fournisseur d'électricité et d'eau, engagé pour un service de qualité et un développement durable.</p>
        <div class="d-flex mt-4">
          <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
          <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
          <a href="#" class="me-3"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="col-md-3 mb-4 mb-md-0">
        <h5>Navigation</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="dashboard.php">Tableau de bord</a></li>
          <li class="mb-2"><a href="clients.php">Clients</a></li>
          <li class="mb-2"><a href="consumption.php">Consommations</a></li>
          <li class="mb-2"><a href="reclamations.php">Réclamations</a></li>
        </ul>
      </div>
      <div class="col-md-5">
        <h5>Contact</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 48, Rue Mohamed Diouri, Casablanca</li>
          <li class="mb-2"><i class="fas fa-phone me-2"></i> 05 22 54 90 00</li>
          <li class="mb-2"><i class="fas fa-envelope me-2"></i> <a href="mailto:service-client@lydec.ma">service-client@lydec.ma</a></li>
          <li class="mb-2"><i class="fas fa-clock me-2"></i> Lun-Ven: 8h00-16h30</li>
        </ul>
      </div>
    </div>
    <div class="border-top border-secondary pt-4 mt-4 text-center">
      <p class="mb-0">&copy; <?php echo date('Y'); ?> - Lydec. Tous droits réservés.</p>
    </div>
  </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
  // Initialize AOS animations
  AOS.init({
    duration: 800,
    once: true
  });

  // Hide page loader after page loads
  window.addEventListener('load', function() {
    const loader = document.querySelector('.page-loader');
    if (loader) {
      loader.classList.add('hidden');
      setTimeout(() => {
        loader.style.display = 'none';
      }, 500);
    }
  });

  // 1. Confirmation de suppression stylée avec SweetAlert2
  function confirmDelete(clientId) {
    Swal.fire({
      title: 'Êtes-vous sûr ?',
      text: "Vous êtes sur le point de supprimer ce client. Cette action est irréversible.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Oui, supprimer',
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
    icon: 'error',
    title: 'Erreur',
    text: 'Cet email est déjà utilisé par un autre client !',
    confirmButtonColor: '#2B6041'
  });
  <?php endif; ?>

  // 3. Affichage d'un message de succès
  <?php if (isset($_GET['success'])): ?>
  Swal.fire({
    icon: 'success',
    title: 'Succès',
    text: '<?php echo $_GET['success'] === 'added' ? 'Client ajouté avec succès !' : ($_GET['success'] === 'edited' ? 'Client modifié avec succès !' : 'Client supprimé avec succès !'); ?>',
    confirmButtonColor: '#2B6041'
  });
  <?php endif; ?>
</script>

</body>
</html>
