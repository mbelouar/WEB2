<?php
session_start();
if (isset($_SESSION['client'])) {
    header("Location: client_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Espace Client</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../assets/css/client-style.css">
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>

<!-- Page Loader -->
<div class="page-loader">
  <img src="../../uploads/Lydec.png" alt="Lydec" class="loader-logo">
</div>

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="../../index.php">
      <img src="../../uploads/Lydec.png" alt="Lydec" class="logo">
      <div class="brand-text">
        <span class="brand-name">Lydec</span>
        <small class="brand-tagline d-none d-sm-inline">Électricité & Eau</small>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarConnexion" 
            aria-controls="navbarConnexion" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarConnexion">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="../../index.php"><i class="fas fa-home me-1"></i>Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="inscription.php"><i class="fas fa-user-plus me-1"></i>Inscription</a></li>
        <li class="nav-item ms-2">
          <button id="darkModeToggle" class="btn btn-sm btn-outline-light">
            <i class="fas fa-moon"></i>
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Formulaire de connexion -->
<div class="container d-flex flex-column justify-content-center align-items-center vh-100 pt-5">
  <div class="auth-card" data-aos="fade-up">
    <div class="text-center mb-4">
      <i class="fas fa-user-circle display-1 text-primary"></i>
    </div>
    <h2 class="card-title text-center mb-4">Connexion Espace Client</h2>
    
    <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>Identifiant ou mot de passe incorrect
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    
    <form action="../../traitement/clientTraitement.php" method="POST">
      <input type="hidden" name="action" value="login">
      <div class="mb-3">
        <label for="clientUsername" class="form-label">
          <i class="fas fa-envelope me-2"></i>Adresse e-mail
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="email" class="form-control" id="clientUsername" placeholder="votreemail@exemple.com" required>
        </div>
      </div>
      <div class="mb-4">
        <label for="clientPassword" class="form-label">
          <i class="fas fa-lock me-2"></i>Mot de passe
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" class="form-control" id="clientPassword" placeholder="Votre mot de passe" required>
          <button class="btn btn-outline-secondary" type="button" id="togglePassword">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2">
        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
      </button>
    </form>
    
    <div class="text-center mt-4">
      <a href="#" class="text-decoration-none">
        <i class="fas fa-question-circle me-1"></i>Mot de passe oublié ?
      </a>
      <div class="mt-3">
        <span class="text-muted">Pas encore inscrit ?</span> 
        <a href="inscription.php" class="ms-1 text-decoration-none">
          Créer un compte
        </a>
      </div>
    </div>
  </div>
  <div class="text-center mt-4">
    <a href="../../index.php" class="btn btn-link text-decoration-none">
      <i class="fas fa-arrow-left me-1"></i>Retour à l'accueil
    </a>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  // Initialize AOS animations
  document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
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

    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
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
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('clientPassword');
    
    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      const icon = togglePassword.querySelector('i');
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });
  });
</script>
</body>
</html>