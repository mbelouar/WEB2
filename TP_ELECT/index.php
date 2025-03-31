<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Lydec Gestion d'Électricité et d'Eau</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css">
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
  <!-- Page Loader -->
  <div class="page-loader">
    <img src="uploads/Lydec.png" alt="Lydec" class="loader-logo">
  </div>

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#accueil">
      <img src="uploads/Lydec.png" alt="Lydec" class="logo">
      <div class="brand-text">
        <span class="brand-name">Lydec</span>
        <small class="brand-tagline d-none d-sm-inline">Électricité & Eau</small>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAccueil" 
            aria-controls="navbarAccueil" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarAccueil">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link active" href="#accueil"><i class="fas fa-home me-1"></i> Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="#features"><i class="fas fa-lightbulb me-1"></i> Fonctionnalités</a></li>
        <li class="nav-item"><a class="nav-link" href="#spaces"><i class="fas fa-user-circle me-1"></i> Espaces</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact"><i class="fas fa-envelope me-1"></i> Contact</a></li>
      </ul>
      <div class="action-buttons d-none d-lg-flex ms-auto">
        <button id="darkModeToggle" class="btn btn-sm btn-outline-light me-2">
          <i class="fas fa-moon"></i>
        </button>
        <a href="IHM/client/connexion.php" class="btn btn-accent btn-sm px-3 btn-connexion">Connexion</a>
      </div>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section id="accueil" class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7 hero-content">
        <h1 class="display-4">
          <span class="welcome-text">Bienvenue sur la Plateforme</span>
          <div class="service-text-wrapper py-2">
            <span class="service-text px-1">Gestion d'Électricité et d'Eau</span>
          </div>
          <span class="company-text mt-2"><img src="uploads/Lydec.png" alt="Lydec" class="hero-logo">Lydec</span>
        </h1>
        <p class="lead my-4">Gérez vos factures, consommation et réclamations en toute simplicité avec notre plateforme moderne et intuitive.</p>
        <div class="hero-buttons">
          <a href="IHM/client/connexion.php" class="btn btn-accent btn-lg btn-connexion"><i class="fas fa-sign-in-alt me-2"></i>Se connecter</a>
        </div>
      </div>
      <div class="col-lg-5 hero-image">
        <img src="assets/images/hero-illustration.svg" alt="Illustration" class="img-fluid">
      </div>
    </div>
</div>
</section>

<!-- Choix d'espace -->
<div id="spaces" class="container py-5">
  <div class="row text-center mb-5">
    <div class="col-lg-8 mx-auto">
      <h2 class="section-title" data-aos="fade-up">Choisissez votre espace</h2>
      <p class="lead" data-aos="fade-up" data-aos-delay="200">Accédez à l'interface adaptée à votre profil pour gérer vos besoins en électricité.</p>
    </div>
  </div>
  
  <div class="row justify-content-center g-4">
    <!-- Espace Fournisseur -->
    <div class="col-md-5" data-aos="fade-up" data-aos-delay="300">
      <div class="card shadow text-center h-100">
        <div class="card-body d-flex flex-column">
          <div class="icon-wrapper mb-4">
            <i class="fas fa-building display-4 text-primary"></i>
          </div>
          <h4 class="card-title mb-3">Espace Fournisseur</h4>
          <p class="card-text flex-grow-1">
            Gérez vos clients, leurs consommations, et émettez des factures facilement grâce à notre interface dédiée.
          </p>
          <a href="IHM/fournisseur/login.php" class="btn btn-primary mt-3">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
          </a>
        </div>
      </div>
    </div>
    
    <!-- Espace Client -->
    <div class="col-md-5" data-aos="fade-up" data-aos-delay="500">
      <div class="card shadow text-center h-100">
        <div class="card-body d-flex flex-column">
          <div class="icon-wrapper mb-4">
            <i class="fas fa-user display-4 text-accent"></i>
          </div>
          <h4 class="card-title mb-3">Espace Client</h4>
          <p class="card-text flex-grow-1">
            Consultez vos factures, saisissez votre consommation, et faites une réclamation en quelques clics.
          </p>
          <a href="IHM/client/connexion.php" class="btn btn-accent mt-3">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Features Section -->
<div id="features" class="bg-light py-5">
  <div class="container py-4">
    <div class="row text-center mb-5">
      <div class="col-lg-8 mx-auto">
        <h2 class="section-title" data-aos="fade-up">Fonctionnalités principales</h2>
        <p class="lead" data-aos="fade-up" data-aos-delay="200">Notre plateforme offre des outils puissants pour gérer votre électricité.</p>
      </div>
    </div>
    
    <div class="row g-4">
      <!-- Feature 1 -->
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mb-3">
            <i class="fas fa-tachometer-alt fa-3x text-primary"></i>
          </div>
          <h4>Suivi de consommation</h4>
          <p>Analysez votre consommation d'électricité avec des graphiques détaillés.</p>
        </div>
      </div>
      
      <!-- Feature 2 -->
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mb-3">
            <i class="fas fa-file-invoice-dollar fa-3x text-warning"></i>
          </div>
          <h4>Gestion des factures</h4>
          <p>Consultez et payez vos factures en ligne en toute sécurité.</p>
        </div>
      </div>
      
      <!-- Feature 3 -->
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="700">
        <div class="feature-card text-center p-4">
          <div class="feature-icon mb-3">
            <i class="fas fa-headset fa-3x text-accent"></i>
          </div>
          <h4>Support client</h4>
          <p>Soumettez et suivez vos réclamations avec notre système de tickets.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Contact Section -->
<div id="contact" class="container py-5 my-5">
  <div class="row">
    <div class="col-lg-8 mx-auto text-center mb-5">
      <h2 class="section-title" data-aos="fade-up">Nous contacter</h2>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-10 mx-auto" data-aos="fade-up">
      <div class="card">
        <div class="card-body p-4">
          <div class="contact-section mb-5">
            <h4 class="text-primary mb-3"><i class="fas fa-globe me-2"></i> Sur le site web www.lydec.ma</h4>
            <p>Pour consulter gratuitement vos comptes, le détail de vos contrats, l'historique de vos consommations… et payer vos factures d'eau et d'électricité en tout sécurité, 24h/24 et 7j/7.</p>
            
            <div class="row mt-4">
              <div class="col-md-3 col-sm-6 mb-3">
                <div class="feature-card text-center p-3 h-100">
                  <i class="fas fa-credit-card text-primary fa-2x mb-2"></i>
                  <h6>Paiement direct</h6>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 mb-3">
                <div class="feature-card text-center p-3 h-100">
                  <i class="fas fa-building text-primary fa-2x mb-2"></i>
                  <h6>Agence en ligne</h6>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 mb-3">
                <div class="feature-card text-center p-3 h-100">
                  <i class="fas fa-info-circle text-primary fa-2x mb-2"></i>
                  <h6>Demande d'information</h6>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 mb-3">
                <div class="feature-card text-center p-3 h-100">
                  <i class="fas fa-exclamation-triangle text-primary fa-2x mb-2"></i>
                  <h6>Réclamation</h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="contact-section mb-5">
            <h4 class="text-primary mb-3"><i class="fas fa-headset me-2"></i> Au Centre de Relation Clientèle</h4>
            <p>A votre service pour répondre à vos demandes d'informations, vos réclamations ou vos demandes de dépannage, par mail au <a href="mailto:contact@lydec.ma" class="text-primary">contact@lydec.ma</a> ou par téléphone au <strong class="text-primary">05 22 31 20 20</strong>.</p>
            
            <div class="row mt-4">
              <div class="col-md-6 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                  <div class="card-body">
                    <h5><i class="fas fa-user-tie text-primary me-2"></i> Service Client :</h5>
                    <ul class="list-unstyled mt-3">
                      <li><i class="fas fa-clock text-secondary me-2"></i> du lundi au vendredi : de 8h00 à 19h00</li>
                      <li><i class="fas fa-clock text-secondary me-2"></i> samedi matin : de 8h00 à 14h30</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                  <div class="card-body">
                    <h5><i class="fas fa-tools text-primary me-2"></i> Service Dépannage :</h5>
                    <p class="mt-3"><i class="fas fa-clock text-secondary me-2"></i> 24h/24 et 7j/7</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="contact-section mb-5">
            <h4 class="text-primary mb-3"><i class="fas fa-mobile-alt me-2"></i> Sur l'application mobile Lydec 7/24</h4>
            <div class="row">
              <div class="col-md-8">
                <p>Lydec a mis à la disposition des clients une application mobile pour les smartphones iPhone et Android qui leur permet d'accéder 24h/24 et 7j/7 à de nombreux services : règlement de factures, localisation de l'agence ou du point de paiement le plus proche, accès aux dernières actualités de Lydec, ou encore un contact simplifié avec le Centre de Relation Clientèle.</p>
              </div>
              <div class="col-md-4 text-center">
                <div class="d-flex justify-content-center align-items-center h-100">
                  <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-apple me-2"></i>App Store</a>
                  <a href="#" class="btn btn-outline-primary"><i class="fab fa-google-play me-2"></i>Play Store</a>
                </div>
              </div>
            </div>
          </div>
          
          <div class="contact-section">
            <h4 class="text-primary mb-3"><i class="fas fa-store-alt me-2"></i> En agence</h4>
            <p>Lydec dispose d'un réseau de 16 agences réparties sur la région de Casablanca et Mohammedia pour accueillir ses clients, les informer et prendre en charge leurs demandes.</p>
            
            <div class="row mt-4">
              <div class="col-md-6 mb-3">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body p-3">
                    <h6 class="card-title"><i class="fas fa-clock text-secondary me-2"></i> Horaires d'ouverture :</h6>
                    <ul class="list-unstyled mb-0 mt-2">
                      <li>Du lundi au jeudi : de 7h30 à 18h00</li>
                      <li>Vendredi : de 7h30 à 11h15 et de 14h30 à 18h00</li>
                      <li>Samedi : de 8h00 à 11h30</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body p-3">
                    <h6 class="card-title"><i class="fas fa-calendar-alt text-secondary me-2"></i> Horaires Ramadan :</h6>
                    <p class="mb-0 mt-2">de 8h30 à 16h00</p>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-4 pt-3 border-top">
              <div class="d-flex align-items-center">
                <i class="fas fa-map-marker-alt fa-2x text-primary me-3"></i>
                <div>
                  <h5 class="mb-0">Siège social :</h5>
                  <p class="mb-0">48, Boulevard Mohamed Diouri - Casablanca</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Pied de page -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
        <div class="footer-brand">
          <img src="uploads/Lydec.png" alt="Lydec" height="40" class="me-2">
        </div>
        <p>Une solution complète et intuitive pour la gestion de vos services d'électricité et d'eau, adaptée aux besoins des habitants de la région de Casablanca-Settat.</p>
        <div class="social-links">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
      
      <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
        <h5>Liens rapides</h5>
        <ul class="list-unstyled">
          <li><a href="#"><i class="fas fa-angle-right me-2"></i>Accueil</a></li>
          <li><a href="#features"><i class="fas fa-angle-right me-2"></i>Fonctionnalités</a></li>
          <li><a href="#spaces"><i class="fas fa-angle-right me-2"></i>Espaces</a></li>
          <li><a href="#contact"><i class="fas fa-angle-right me-2"></i>Contact</a></li>
        </ul>
      </div>
      
      <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
        <h5>Services</h5>
        <ul class="list-unstyled">
          <li><a href="#"><i class="fas fa-angle-right me-2"></i>Gestion de factures</a></li>
          <li><a href="#"><i class="fas fa-angle-right me-2"></i>Suivi de consommation</a></li>
          <li><a href="#"><i class="fas fa-angle-right me-2"></i>Gestion des réclamations</a></li>
          <li><a href="#"><i class="fas fa-angle-right me-2"></i>Support client</a></li>
        </ul>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <h5>Newsletter</h5>
        <p>Abonnez-vous pour recevoir nos actualités et offres spéciales.</p>
        <form class="mt-3">
          <div class="input-group">
            <input type="email" class="form-control" placeholder="Votre email">
            <button class="btn btn-accent" type="button">
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
    
    <hr>
    
    <div class="footer-bottom">
      <div class="small">&copy; 2025 Lydec - Tous droits réservés</div>
      <div>
        <a href="#" class="me-3 small">Politique de confidentialité</a>
        <a href="#" class="small">Conditions d'utilisation</a>
      </div>
    </div>
  </div>
</footer>

<!-- Scroll to top button -->
<div class="scroll-top">
  <i class="fas fa-angle-up"></i>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Handle page loader
    const pageLoader = document.querySelector('.page-loader');
    
    window.addEventListener('load', function() {
      setTimeout(function() {
        pageLoader.classList.add('loaded');
      }, 500);
    });
    
    // Initialize AOS animations with enhanced configuration
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
      offset: 100,
      delay: 100
    });

    // DOM Elements
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    const scrollTopBtn = document.querySelector('.scroll-top');
    
    // Create a map of href values to their corresponding links
    const hrefToLinkMap = {};
    navLinks.forEach(link => {
      const href = link.getAttribute('href');
      hrefToLinkMap[href] = link;
    });
    
    // Function to check if element is in viewport
    function isElementInViewport(el, offset = 0) {
      const rect = el.getBoundingClientRect();
      return (
        rect.top <= offset && 
        rect.bottom >= 0
      );
    }
    
    // Handle navbar appearance on scroll
    function handleNavbarScroll() {
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
      
      // Show/hide scroll to top button
      if (window.scrollY > 300) {
        scrollTopBtn.classList.add('visible');
      } else {
        scrollTopBtn.classList.remove('visible');
      }
    }
    
    // Toggle dark mode
    function toggleDarkMode() {
      body.classList.toggle('dark-mode');
      const isDarkMode = body.classList.contains('dark-mode');
      localStorage.setItem('darkMode', isDarkMode);
      
      // Update icon
      const icon = darkModeToggle.querySelector('i');
      if (isDarkMode) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
      } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
      }
    }
    
    // Check for saved dark mode preference
    function checkDarkModePreference() {
      if (localStorage.getItem('darkMode') === 'true') {
        body.classList.add('dark-mode');
        const icon = darkModeToggle.querySelector('i');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
      }
    }
    
    // Get all sections that correspond to navigation items
    const sections = [
      { id: 'accueil', element: document.getElementById('accueil') },
      { id: 'spaces', element: document.getElementById('spaces') },
      { id: 'features', element: document.getElementById('features') },
      { id: 'contact', element: document.getElementById('contact') }
    ];
    
    // Update active navigation link based on scroll position
    let isManualScrolling = false;
    let scrollTimeout;
    
    function updateActiveNavLink() {
      if (isManualScrolling) return;
      
      // Get current scroll position with a small offset
      const scrollPosition = window.scrollY + window.innerHeight * 0.3;
      
      // Find the current section by checking from bottom to top (for better accuracy)
      let currentSection = null;
      
      for (let i = sections.length - 1; i >= 0; i--) {
        const section = sections[i];
        if (section.element && section.element.offsetTop <= scrollPosition) {
          currentSection = section;
          break;
        }
      }
      
      // Special case for top of page
      if (window.scrollY < 100) {
        currentSection = sections[0]; // Default to first section when at top
      }
      
      // Update active class
      if (currentSection) {
        navLinks.forEach(link => link.classList.remove('active'));
        const activeLink = hrefToLinkMap[`#${currentSection.id}`];
        if (activeLink) activeLink.classList.add('active');
      }
    }
    
    // Add click handlers to navigation links
    navLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Get target section
        const href = this.getAttribute('href');
        const targetSection = document.querySelector(href);
        
        if (targetSection) {
          // Set active state immediately for better UX
          navLinks.forEach(link => link.classList.remove('active'));
          this.classList.add('active');
          
          // Enable manual scrolling flag to prevent flickering
          isManualScrolling = true;
          clearTimeout(scrollTimeout);
          
          // Close mobile menu if open
          const navbarCollapse = document.querySelector('.navbar-collapse');
          if (navbarCollapse.classList.contains('show')) {
            const bsCollapse = new bootstrap.Collapse(navbarCollapse);
            bsCollapse.hide();
          }
          
          // Smooth scroll to target
          const headerOffset = 80;
          const targetPosition = targetSection.offsetTop - headerOffset;
          
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
          
          // Reset manual scrolling flag after animation completes
          setTimeout(() => {
            isManualScrolling = false;
          }, 1000);
        }
      });
    });
    
    // Scroll to top when the button is clicked
    scrollTopBtn.addEventListener('click', function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
    
    // Event listeners
    window.addEventListener('scroll', handleNavbarScroll);
    window.addEventListener('scroll', function() {
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(updateActiveNavLink, 100);
    });
    darkModeToggle.addEventListener('click', toggleDarkMode);
    
    // Initialize state
    handleNavbarScroll();
    updateActiveNavLink();
    checkDarkModePreference();
    
    // Detect when user manually scrolls
    window.addEventListener('wheel', function() {
      isManualScrolling = false;
    });
    window.addEventListener('touchmove', function() {
      isManualScrolling = false;
    });
    
    // Add hover effect to feature cards
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.querySelector('.feature-icon').classList.add('animated');
      });
    });

    // Update click handler for Connexion buttons to scroll to Espaces section
    const connexionButtons = document.querySelectorAll('.btn-connexion');
    connexionButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default navigation to IHM/client/connexion.php
        const espacesSection = document.getElementById('spaces');
        if (espacesSection) {
          const headerOffset = 80; // Adjust based on your fixed header height
          const elementPosition = espacesSection.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
          
          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });
        }
      });
    });
  });
</script>
</body>
</html>
