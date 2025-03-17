<?php
require '../BD/connexion.php';  // Connexion à la base de données

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des données
    $errors = [];
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $date_public = $_POST['date_public'];
    $date_fin = $_POST['date_fin'];
    $porteur = trim($_POST['porteur']);
    $email = trim($_POST['email']);
    
    // Validation simple
    if (empty($titre)) $errors[] = "Le titre est obligatoire";
    if (empty($description)) $errors[] = "La description est obligatoire";
    if (empty($date_public)) $errors[] = "La date de publication est obligatoire";
    if (empty($date_fin)) $errors[] = "La date de fin est obligatoire";
    if (empty($porteur)) $errors[] = "Le nom du porteur est obligatoire";
    if (empty($email)) $errors[] = "L'email est obligatoire";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'email n'est pas valide";
    
    if (empty($errors)) {
        try {
            $query = "INSERT INTO Petition (Titre, Description, DatePublic, DateFinP, PorteurP, Email)
                    VALUES (:titre, :description, :date_public, :date_fin, :porteur, :email)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':titre' => $titre,
                ':description' => $description,
                ':date_public' => $date_public,
                ':date_fin' => $date_fin,
                ':porteur' => $porteur,
                ':email' => $email
            ]);
            $success_message = "Votre pétition a été créée avec succès !";
            
            // Vider les données du formulaire après succès
            $titre = $description = $date_public = $date_fin = $porteur = $email = '';
        } catch (PDOException $e) {
            $error_message = "Une erreur est survenue: " . $e->getMessage();
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Pétition | PetitionHub</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><i class="fas fa-pen-fancy"></i> PetitionHub</h1>
            <p class="tagline">Donnez vie à votre cause</p>
            <div class="nav-links">
                <a href="index.php"><i class="fas fa-home"></i> Accueil</a>
                <a href="liste_petition.php"><i class="fas fa-list"></i> Voir les pétitions</a>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="page-header fade-in">
            <h2>Créer une nouvelle pétition</h2>
            <p>Complétez le formulaire ci-dessous pour lancer votre pétition et commencer à rassembler des signatures</p>
        </section>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                <div class="alert-actions">
                    <a href="liste_petition.php" class="btn btn-sm">Voir toutes les pétitions</a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error fade-in">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Add animations and enhanced effects -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add form field animations
                document.querySelectorAll('.form-group input, .form-group textarea').forEach(input => {
                    // Initially check if the field has a value (for form resubmission cases)
                    if (input.value !== '') {
                        input.closest('.form-group').classList.add('has-value');
                    }
                    
                    // Add focus styles
                    input.addEventListener('focus', function() {
                        this.closest('.form-group').classList.add('focused');
                    });
                    
                    // Remove focus styles if empty
                    input.addEventListener('blur', function() {
                        if (this.value === '') {
                            this.closest('.form-group').classList.remove('focused', 'has-value');
                        } else {
                            this.closest('.form-group').classList.add('has-value');
                            this.closest('.form-group').classList.remove('focused');
                        }
                    });
                });
                
                // Add button shine effect
                document.querySelectorAll('.btn').forEach(btn => {
                    btn.classList.add('shine-effect');
                });
                
                // Add section entrance animations
                const animateSections = () => {
                    const sections = document.querySelectorAll('.form-section');
                    sections.forEach((section, index) => {
                        setTimeout(() => {
                            section.classList.add('animate-in');
                        }, 200 * index);
                    });
                };
                
                animateSections();
            });
        </script>

        <style>
            /* Enhanced petition form styles */
            .page-header {
                text-align: center;
                margin: 2rem 0 3rem;
                position: relative;
            }
            
            .page-header::after {
                content: '';
                position: absolute;
                bottom: -15px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 4px;
                background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
                border-radius: 2px;
            }
            
            .petition-form {
                max-width: 900px;
                margin: 0 auto;
                position: relative;
            }
            
            .petition-form::before {
                content: '';
                position: absolute;
                top: -20px;
                right: -20px;
                width: 100px;
                height: 100px;
                background: var(--accent-bright);
                opacity: 0.1;
                border-radius: 50%;
                z-index: 0;
            }
            
            /* Form sections */
            .form-section {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.6s ease;
            }
            
            .form-section.animate-in {
                opacity: 1;
                transform: translateY(0);
            }
            
            .form-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
                margin-bottom: 0.5rem;
            }
            
            .form-group {
                margin-bottom: 1.5rem;
                position: relative;
            }
            
            .form-group label {
                transition: all 0.3s ease;
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
                color: var(--text-color);
            }
            
            .form-group.focused label {
                color: var(--accent-color);
                transform: translateY(-5px);
            }
            
            .form-group.has-value label {
                color: var(--primary-dark);
            }
            
            .form-group input, .form-group textarea {
                transition: all 0.3s ease;
                border: 2px solid #e0e0e0;
            }
            
            .form-group.focused input, .form-group.focused textarea {
                border-color: var(--accent-color);
                box-shadow: 0 0 0 3px rgba(240, 147, 25, 0.1);
            }
            
            .form-group.has-value input, .form-group.has-value textarea {
                border-color: var(--primary-color);
            }
            
            .form-help {
                font-size: 0.85rem;
                color: var(--text-light);
                margin-top: 0.5rem;
                transition: all 0.3s ease;
            }
            
            .form-group.focused .form-help {
                color: var(--accent-color);
            }
            
            .form-divider {
                height: 1px;
                background: linear-gradient(to right, var(--primary-color), transparent);
                margin: 2rem 0;
                position: relative;
            }
            
            .form-divider::before {
                content: '';
                position: absolute;
                left: 0;
                top: -5px;
                width: 10px;
                height: 10px;
                background: var(--accent-color);
                border-radius: 50%;
            }
            
            .form-section-title {
                font-size: 1.3rem;
                margin-bottom: 1.5rem;
                color: var(--primary-dark);
                position: relative;
                padding-left: 15px;
            }
            
            .form-section-title::before {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 5px;
                height: 25px;
                background: var(--accent-color);
                border-radius: 3px;
            }
            
            .form-actions {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-top: 2rem;
            }
            
            /* Button animations */
            .btn-lg {
                padding: 1rem 2.5rem;
                font-size: 1.2rem;
                position: relative;
                overflow: hidden;
                background: linear-gradient(135deg, var(--accent-color), var(--primary-dark));
            }
            
            .shine-effect {
                position: relative;
                overflow: hidden;
            }
            
            .shine-effect::after {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
                transform: rotate(30deg);
                animation: shine 6s infinite;
            }
            
            @keyframes shine {
                0% { transform: rotate(30deg) translateX(-300%); }
                30% { transform: rotate(30deg) translateX(100%); }
                100% { transform: rotate(30deg) translateX(100%); }
            }
            
            .btn-link {
                color: var(--text-light);
                text-decoration: none;
                margin-top: 1rem;
                transition: var(--transition);
                position: relative;
            }
            
            .btn-link::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 0;
                height: 2px;
                background: var(--accent-color);
                transition: width 0.3s ease;
            }
            
            .btn-link:hover {
                color: var(--accent-color);
            }
            
            .btn-link:hover::after {
                width: 100%;
            }
            
            /* Alert styles */
            .alert {
                padding: 1.2rem;
                border-radius: var(--border-radius);
                margin-bottom: 2rem;
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                position: relative;
                overflow: hidden;
                animation: slideDown 0.5s ease;
            }
            
            .alert::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 5px;
            }
            
            .alert i {
                margin-right: 0.5rem;
                font-size: 1.2rem;
            }
            
            .alert-success {
                background-color: rgba(171, 186, 124, 0.2);
                color: var(--primary-dark);
                border: 1px solid rgba(171, 186, 124, 0.3);
            }
            
            .alert-success::before {
                background-color: var(--primary-color);
            }
            
            .alert-success i {
                color: var(--primary-color);
            }
            
            .alert-error {
                background-color: rgba(240, 147, 25, 0.1);
                color: var(--accent-color);
                border: 1px solid rgba(240, 147, 25, 0.3);
            }
            
            .alert-error::before {
                background-color: var(--accent-color);
            }
            
            .alert-error i {
                color: var(--accent-color);
            }
            
            .alert-actions {
                margin-left: auto;
            }
            
            .btn-sm {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            @media screen and (max-width: 768px) {
                .form-row {
                    grid-template-columns: 1fr;
                    gap: 0;
                }
            }
        </style>

        <!-- Update the form to use section animations -->
        <form method="POST" action="ajouter_petition.php" class="petition-form card fade-in">
            <div class="form-section">
                <div class="form-group">
                    <label for="titre"><i class="fas fa-heading"></i> Titre de la pétition</label>
                    <input type="text" id="titre" name="titre" required placeholder="Un titre concis et impactant pour votre pétition" value="<?php echo isset($titre) ? htmlspecialchars($titre) : ''; ?>">
                    <div class="form-help">Choisissez un titre clair qui résume bien votre cause</div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label for="description"><i class="fas fa-align-left"></i> Description détaillée</label>
                    <textarea id="description" name="description" required placeholder="Expliquez votre cause, pourquoi elle est importante et quel changement vous souhaitez voir"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                    <div class="form-help">Soyez précis et persuasif pour convaincre les autres de signer</div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_public"><i class="fas fa-calendar-plus"></i> Date de publication</label>
                        <input type="date" id="date_public" name="date_public" required value="<?php echo isset($date_public) ? $date_public : date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="date_fin"><i class="fas fa-calendar-times"></i> Date de fin</label>
                        <input type="date" id="date_fin" name="date_fin" required value="<?php echo isset($date_fin) ? $date_fin : date('Y-m-d', strtotime('+30 days')); ?>">
                        <div class="form-help">Date limite pour la collecte des signatures</div>
                    </div>
                </div>
            </div>

            <div class="form-divider"></div>
            
            <div class="form-section">
                <h3 class="form-section-title">Vos informations</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="porteur"><i class="fas fa-user"></i> Votre nom</label>
                        <input type="text" id="porteur" name="porteur" required placeholder="Prénom et nom" value="<?php echo isset($porteur) ? htmlspecialchars($porteur) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Votre email</label>
                        <input type="email" id="email" name="email" required placeholder="email@exemple.com" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                        <div class="form-help">Nous ne partagerons jamais votre email</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-actions">
                    <button type="submit" class="btn btn-lg"><i class="fas fa-paper-plane"></i> Publier ma pétition</button>
                    <a href="index.php" class="btn-link">Annuler et revenir à l'accueil</a>
                </div>
            </div>
        </form>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 PetitionHub - Tous droits réservés</p>
        </div>
    </footer>
</body>
</html>
