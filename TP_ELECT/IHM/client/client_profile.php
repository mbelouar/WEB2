<?php
session_start();

// Define API_REQUEST constant to prevent any text output from db.php
define('API_REQUEST', true);

require_once '../../BD/db.php';
require_once '../../BD/Client.php';
require_once '../../BD/Consumption.php';
require_once '../../BD/Facture.php';

// Set page variables
$pageTitle = 'Mon Profil';
$activePage = 'profile';

// Check if the user is logged in
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

// Get client info
$clientId = $_SESSION['client']['id'];
$clientModel = new Client($pdo);
$client = $clientModel->getProfile($clientId);

// Get additional data
$consumptionModel = new Consumption($pdo);
$factureModel = new Facture($pdo);

// Get latest consumption reading
$lastConsumption = $consumptionModel->getLastConsumption($clientId);
$lastReading = $lastConsumption ? $lastConsumption['current_reading'] : null;

// Get unpaid balance
$factures = $factureModel->getFacturesByClient($clientId);
$balance = 0;
if ($factures) {
    foreach ($factures as $facture) {
        if ($facture['statut'] === 'impayée') {
            $balance += $facture['montant'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle; ?> - Lydec Espace Client</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../assets/css/client-style.css">
  
  <style>
    :root {
      /* Primary Colors */
      --primary: #2B6041;
      --primary-light: #3F7D58;
      --primary-dark: #1D4B2F;
      
      /* Secondary Colors */
      --secondary: #4CA1AF;
      --secondary-light: #6BBAC8;
      --secondary-dark: #378896;
      
      /* Accent Colors */
      --accent: #EF9651;
      --accent-light: #F2AC74;
      --accent-dark: #D97C34;
    }
    
    .profile-icon {
      width: 120px;
      height: 120px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background-color: rgba(43, 96, 65, 0.1);
    }
    
    .info-row {
      display: flex;
      margin-bottom: 15px;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      padding-bottom: 15px;
    }
    
    .info-label {
      flex-basis: 120px;
      font-weight: 600;
      color: var(--dark-gray);
    }
    
    .info-value {
      flex-grow: 1;
    }
    
    .stat-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
      height: 100%;
      transition: transform 0.3s;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
    }
    
    .stat-icon {
      font-size: 2.5rem;
      opacity: 0.2;
      position: absolute;
      bottom: 10px;
      right: 10px;
      color: var(--primary);
    }
    
    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #333;
    }
    
    .stat-label {
      color: var(--dark-gray);
      font-size: 0.9rem;
      text-transform: uppercase;
      margin-top: 5px;
    }
    
    .navbar {
      background: var(--gradient-primary) !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .btn-accent {
      background-color: var(--accent);
      border-color: var(--accent);
      color: white;
    }
    
    .btn-accent:hover {
      background-color: var(--accent-dark);
      border-color: var(--accent-dark);
      color: white;
    }
    
    .text-primary {
      color: var(--primary) !important;
    }
    
    footer {
      background: var(--gradient-footer);
      color: #fff;
      padding: 3rem 0 1.5rem;
      margin-top: 2rem;
    }
    
    footer a {
      color: #fff;
      opacity: 0.8;
      text-decoration: none;
      transition: opacity 0.3s;
    }
    
    footer a:hover {
      opacity: 1;
      color: #fff;
    }
    
    .card-header {
      background-color: rgba(43, 96, 65, 0.05);
      border-bottom: 1px solid rgba(43, 96, 65, 0.1);
    }
    
    .font-weight-bold {
      font-weight: 600;
      color: var(--primary-dark);
    }
  </style>
</head>
<body>
  <!-- Page Loader -->
  <div class="page-loader">
    <img src="../../uploads/Lydec.png" alt="Lydec" class="loader-logo">
  </div>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="../../uploads/Lydec.png" alt="Lydec" class="logo">
        <div class="brand-text">
          <span class="brand-name">Lydec</span>
          <small class="brand-tagline d-none d-sm-inline">Électricité & Eau</small>
        </div>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#clientNavbar" 
              aria-controls="clientNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="clientNavbar">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="client_dashboard.php">
              <i class="fas fa-tachometer-alt me-1"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="client_invoices.php">
              <i class="fas fa-file-invoice-dollar me-1"></i> Mes Factures
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="client_new_consumption.php">
              <i class="fas fa-bolt me-1"></i> Saisir Conso
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="client_complaint.php">
              <i class="fas fa-comment-alt me-1"></i> Réclamation
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li>
                <a class="dropdown-item active" href="client_profile.php">
                  <i class="fas fa-id-card me-2"></i> Mon Profil
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item text-danger" href="#" onclick="confirmLogout(event)">
                  <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                </a>
              </li>
            </ul>
          </li>
        </ul>
        <div class="ms-lg-3">
          <button id="darkModeToggle" class="btn btn-sm btn-outline-light">
            <i class="fas fa-moon"></i>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="py-4">
    <div class="container my-4">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0" data-aos="fade-right">
          <i class="fas fa-id-card me-2 text-primary"></i>
          Mon Profil
        </h1>
      </div>

      <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-5 mb-4" data-aos="fade-up">
          <div class="card shadow h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
              <h5 class="m-0 font-weight-bold"><i class="fas fa-user-circle me-2"></i>Informations personnelles</h5>
            </div>
            <div class="card-body">
              <?php if ($client): ?>
              <div class="text-center mb-4">
                <div class="profile-icon">
                  <i class="fas fa-user-circle fa-6x text-primary"></i>
                </div>
                <h4 class="mt-3"><?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?></h4>
                <span class="badge bg-primary">Client</span>
              </div>
              
              <div class="profile-details">
                <div class="info-row">
                  <div class="info-label"><i class="fas fa-id-card me-2"></i>CIN</div>
                  <div class="info-value"><?php echo htmlspecialchars($client['cin']); ?></div>
                </div>
                <div class="info-row">
                  <div class="info-label"><i class="fas fa-envelope me-2"></i>Email</div>
                  <div class="info-value"><?php echo htmlspecialchars($client['email']); ?></div>
                </div>
                <div class="info-row">
                  <div class="info-label"><i class="fas fa-phone me-2"></i>Téléphone</div>
                  <div class="info-value"><?php echo htmlspecialchars($client['telephone']); ?></div>
                </div>
                <div class="info-row">
                  <div class="info-label"><i class="fas fa-map-marker-alt me-2"></i>Adresse</div>
                  <div class="info-value"><?php echo htmlspecialchars($client['adresse']); ?></div>
                </div>
              </div>
              <?php else: ?>
              <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Impossible de charger les informations du profil.
              </div>
              <?php endif; ?>
            </div>
            <div class="card-footer">
              <small class="text-muted">Client ID: <?php echo $clientId; ?></small>
            </div>
          </div>
        </div>
        
        <!-- Stats & Actions Card -->
        <div class="col-lg-7 mb-4" data-aos="fade-up" data-aos-delay="200">
          <div class="card shadow h-100">
            <div class="card-header py-3">
              <h5 class="m-0 font-weight-bold"><i class="fas fa-chart-pie me-2"></i>Votre Consommation</h5>
            </div>
            <div class="card-body">
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <div class="stat-card">
                    <div class="stat-value">
                      <?php echo $lastReading ? $lastReading : '0'; ?>
                      <small>kWh</small>
                    </div>
                    <div class="stat-label">Dernière Lecture</div>
                    <i class="fas fa-bolt stat-icon"></i>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="stat-card">
                    <div class="stat-value">
                      <?php echo number_format($balance, 2); ?>
                      <small>DH</small>
                    </div>
                    <div class="stat-label">Solde Actuel</div>
                    <i class="fas fa-money-bill-wave stat-icon"></i>
                  </div>
                </div>
              </div>
              
              <div class="text-center mt-4">
                <a href="client_invoices.php" class="btn btn-outline-primary me-2">
                  <i class="fas fa-file-invoice me-1"></i> Voir Mes Factures
                </a>
                <a href="client_new_consumption.php" class="btn btn-accent">
                  <i class="fas fa-tachometer-alt me-1"></i> Saisir Consommation
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

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
            <li class="mb-2"><a href="client_dashboard.php">Dashboard</a></li>
            <li class="mb-2"><a href="client_invoices.php">Mes Factures</a></li>
            <li class="mb-2"><a href="client_new_consumption.php">Saisir Consommation</a></li>
            <li class="mb-2"><a href="client_complaint.php">Réclamation</a></li>
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

  <!-- Confirmation Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation de déconnexion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Êtes-vous sûr de vouloir vous déconnecter ?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <a href="logout.php" class="btn btn-danger">Déconnexion</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- AOS Animation JS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  
  <!-- Custom JS -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize AOS animation library
      AOS.init({
        duration: 800,
        once: true
      });
      
      // Hide page loader after page is fully loaded
      setTimeout(function() {
        const pageLoader = document.querySelector('.page-loader');
        if (pageLoader) {
          pageLoader.classList.add('hidden');
          setTimeout(function() {
            pageLoader.style.display = 'none';
          }, 500);
        }
      }, 300);
      
      // Dark mode toggle
      const darkModeToggle = document.getElementById('darkModeToggle');
      if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
          document.body.classList.toggle('dark-mode');
          const icon = darkModeToggle.querySelector('i');
          if (document.body.classList.contains('dark-mode')) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
          } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
          }
        });
      }
      
      // Show any alerts with fade effect
      setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
          const closeBtn = alert.querySelector('.btn-close');
          if (closeBtn) {
            closeBtn.addEventListener('click', function() {
              alert.classList.add('fade');
              setTimeout(function() {
                alert.remove();
              }, 300);
            });
          }
        });
      }, 100);
    });
    
    // Logout confirmation
    function confirmLogout(e) {
      e.preventDefault();
      var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
      logoutModal.show();
    }
  </script>
</body>
</html>
