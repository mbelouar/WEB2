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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Espace Fournisseur</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../style.css">
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="../../index.php">
      <i class="fas fa-bolt me-2"></i>Gestion Électricité
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarConnexion" 
            aria-controls="navbarConnexion" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarConnexion">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="../../index.php">
            <i class="fas fa-home me-1"></i>Retour à l'accueil
          </a>
        </li>
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
    <h2 class="card-title text-center mb-4">Connexion Fournisseur</h2>
    
    <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>Identifiant ou mot de passe incorrect
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    
    <form action="../../traitement/fournisseurTraitement.php" method="POST">
      <input type="hidden" name="action" value="login">
      <div class="mb-3">
        <label for="username" class="form-label">
          <i class="fas fa-envelope me-2"></i>Adresse e-mail
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" class="form-control" name="emailF" id="username" placeholder="votreemail@exemple.com" required>
        </div>
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">
          <i class="fas fa-lock me-2"></i>Mot de passe
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" name="passwordF" id="password" placeholder="Votre mot de passe" required>
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
    </div>
    <div class="text-center mt-4">
      <a href="../../index.php" class="btn btn-link text-decoration-none">
        <i class="fas fa-arrow-left me-1"></i>Retour à l'accueil
      </a>
    </div>
  </div>
</div>

<!-- Dark mode script -->
<script>
  // Toggle dark mode
  const darkModeToggle = document.getElementById('darkModeToggle');
  const body = document.body;
  
  // Check for saved preference
  const savedPreference = localStorage.getItem('darkMode');
  if (savedPreference) {
    body.classList.toggle('dark-mode', savedPreference === 'enabled');
  }
  
  darkModeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    const isDarkMode = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
    darkModeToggle.querySelector('i').classList.toggle('fa-moon', !isDarkMode);
    darkModeToggle.querySelector('i').classList.toggle('fa-sun', isDarkMode);
  });
</script>

<!-- Password toggle script -->
<script>
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  
  togglePassword.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.querySelector('i').classList.toggle('fa-eye');
    this.querySelector('i').classList.toggle('fa-eye-slash');
  });
</script>

<!-- Initialize AOS animations -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    once: true
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
