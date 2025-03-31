<?php
// Define title and active page (should be set before including this template)
$pageTitle = $pageTitle ?? 'Mon Espace';
$activePage = $activePage ?? '';

// Client ID check
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

// Get client info
$clientId = $_SESSION['client']['id'];
$clientName = $_SESSION['client']['nom'] . ' ' . $_SESSION['client']['prenom'];
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
  
  <!-- Page-specific CSS (if needed) -->
  <?php if (isset($pageSpecificCSS)): ?>
    <?php echo $pageSpecificCSS; ?>
  <?php endif; ?>
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
            <a class="nav-link <?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>" href="client_dashboard.php">
              <i class="fas fa-tachometer-alt me-1"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($activePage === 'invoices') ? 'active' : ''; ?>" href="client_invoices.php">
              <i class="fas fa-file-invoice-dollar me-1"></i> Mes Factures
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($activePage === 'consumption') ? 'active' : ''; ?>" href="client_new_consumption.php">
              <i class="fas fa-bolt me-1"></i> Saisir Conso
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($activePage === 'complaint') ? 'active' : ''; ?>" href="client_complaint.php">
              <i class="fas fa-comment-alt me-1"></i> Réclamation
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($clientName); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li>
                <a class="dropdown-item" href="client_profile.php">
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
    <!-- Page content will be injected here -->
    <?php if (isset($pageContent)): ?>
      <?= $pageContent ?>
    <?php else: ?>
      <div class="container my-4">
        <!-- Default content placeholder -->
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-3">Chargement du contenu...</p>
        </div>
      </div>
    <?php endif; ?>
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
  
  <!-- AOS Animation Library -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  
  <!-- Custom JS -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize AOS animation library
      AOS.init({
        duration: 800,
        once: true
      });
      
      // Handle page loader
      const pageLoader = document.querySelector('.page-loader');
      
      window.addEventListener('load', function() {
        setTimeout(function() {
          pageLoader.classList.add('hidden');
          setTimeout(function() {
            pageLoader.style.display = 'none';
          }, 500);
        }, 500);
      });
      
      // Navbar scroll effect
      const navbar = document.querySelector('.navbar');
      
      window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      });
      
      // Dark mode toggle
      const darkModeToggle = document.getElementById('darkModeToggle');
      const html = document.querySelector('html');
      
      // Check for saved theme preference
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        html.classList.add('dark-mode');
        darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
      }
      
      darkModeToggle.addEventListener('click', function() {
        html.classList.toggle('dark-mode');
        
        // Save theme preference
        if (html.classList.contains('dark-mode')) {
          localStorage.setItem('theme', 'dark');
          darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        } else {
          localStorage.setItem('theme', 'light');
          darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        }
      });
      
      // Add animation to all cards on page
      const cards = document.querySelectorAll('.card');
      cards.forEach((card, index) => {
        card.setAttribute('data-aos', 'fade-up');
        card.setAttribute('data-aos-delay', (index * 100).toString());
      });
    });
    
    // Logout confirmation
    function confirmLogout(event) {
      event.preventDefault();
      const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
      logoutModal.show();
    }
  </script>
  
  <!-- Page-specific JS (if needed) -->
  <?php if (isset($pageSpecificJS)): ?>
    <?php echo $pageSpecificJS; ?>
  <?php endif; ?>
</body>
</html>
