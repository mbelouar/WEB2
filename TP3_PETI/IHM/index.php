<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetitionHub - Accueil</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><i class="fas fa-pen-fancy"></i> PetitionHub</h1>
            <p class="tagline">Votre voix compte, faites-la entendre</p>
            <div class="nav-links">
                <a href="ajouter_petition.php"><i class="fas fa-plus-circle"></i> Créer une pétition</a>
                <a href="liste_petition.php"><i class="fas fa-list"></i> Voir les pétitions</a>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="hero fade-in">
            <div class="hero-content">
                <h2>Changez le monde avec votre signature</h2>
                <p>PetitionHub est une plateforme permettant de créer et signer des pétitions en ligne pour soutenir des causes qui vous tiennent à cœur.</p>
                <a href="liste_petition.php" class="btn"><i class="fas fa-arrow-right"></i> Découvrir les pétitions</a>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1494172961521-33799ddd43a5?auto=format&fit=crop&w=600&q=80" alt="People signing petition">
            </div>
        </section>

        <section class="features">
            <div class="card feature-card fade-in">
                <i class="fas fa-edit feature-icon"></i>
                <h3>Créez une pétition</h3>
                <p>Lancez une pétition pour une cause qui vous tient à cœur et partagez-la avec le monde.</p>
            </div>
            <div class="card feature-card fade-in">
                <i class="fas fa-signature feature-icon"></i>
                <h3>Signez des pétitions</h3>
                <p>Soutenez des causes importantes en ajoutant votre signature à des pétitions existantes.</p>
            </div>
            <div class="card feature-card fade-in">
                <i class="fas fa-chart-line feature-icon"></i>
                <h3>Suivez la progression</h3>
                <p>Surveillez le nombre de signatures et l'impact de votre pétition en temps réel.</p>
            </div>
        </section>

        <section class="cta fade-in">
            <h2>Prêt à faire entendre votre voix ?</h2>
            <p>Rejoignez des milliers de personnes qui font une différence grâce à PetitionHub.</p>
            <div class="cta-buttons">
                <a href="ajouter_petition.php" class="btn"><i class="fas fa-plus-circle"></i> Créer une pétition</a>
                <a href="liste_petition.php" class="btn btn-outline"><i class="fas fa-search"></i> Explorer les pétitions</a>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 PetitionHub - Tous droits réservés</p>
        </div>
    </footer>

    <script>
        // Add any JavaScript needed for the homepage animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate elements when they come into view
            const fadeElements = document.querySelectorAll('.fade-in');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });
            
            fadeElements.forEach(element => {
                observer.observe(element);
            });
        });
    </script>
</body>
</html>
