<?php
require '../BD/signature.php';
require '../BD/connexion.php';

// Get petition ID from URL
$idp = isset($_GET['id']) ? $_GET['id'] : 0;

// Get petition details
$queryPetition = "SELECT * FROM Petition WHERE IDP = :idp";
$stmtPetition = $pdo->prepare($queryPetition);
$stmtPetition->execute([':idp' => $idp]);
$petition = $stmtPetition->fetch(PDO::FETCH_ASSOC);

// Get signatures
$signatures = getSignatures($idp);

// Count total signatures
$queryCount = "SELECT COUNT(*) as total FROM Signature WHERE IDP = :idp";
$stmtCount = $pdo->prepare($queryCount);
$stmtCount->execute([':idp' => $idp]);
$signatureCount = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signer la pétition | PetitionHub</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><i class="fas fa-pen-fancy"></i> PetitionHub</h1>
            <p class="tagline">Votre signature compte</p>
            <div class="nav-links">
                <a href="index.php"><i class="fas fa-home"></i> Accueil</a>
                <a href="liste_petition.php"><i class="fas fa-list"></i> Voir les pétitions</a>
            </div>
        </div>
    </header>

    <main class="container">
        <?php if ($petition): ?>
            <section class="petition-detail card fade-in">
                <h2><?php echo htmlspecialchars($petition['Titre']); ?></h2>
                <div class="petition-meta">
                    <span><i class="fas fa-user"></i> Par <?php echo htmlspecialchars($petition['PorteurP']); ?></span>
                    <span><i class="fas fa-signature"></i> <span class="counter-value" data-count="<?php echo $signatureCount; ?>">0</span> signatures</span>
                    <span><i class="fas fa-calendar-times"></i> Jusqu'au <?php echo date('d/m/Y', strtotime($petition['DateFinP'])); ?></span>
                </div>
                <p class="petition-description"><?php echo nl2br(htmlspecialchars($petition['Description'])); ?></p>
            </section>

            <div class="content-grid">
                <section class="signature-form card fade-in">
                    <h3><i class="fas fa-pen-nib"></i> Signer cette pétition</h3>
                    <p class="form-intro">Ajoutez votre signature pour soutenir cette cause</p>
                    
                    <form action="../Traitement/ajouter_signature.php" method="POST" class="fade-in">
                        <input type="hidden" name="idp" value="<?php echo $idp; ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom"><i class="fas fa-user"></i> Nom</label>
                                <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="prenom"><i class="fas fa-user"></i> Prénom</label>
                                <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="pays"><i class="fas fa-globe"></i> Pays</label>
                                <input type="text" id="pays" name="pays" placeholder="Votre pays" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" id="email" name="email" placeholder="votre.email@exemple.com" required>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-signature"></i> Signer maintenant</button>
                        </div>
    </form>
                </section>

                <section class="signature-list card fade-in">
                    <h3><i class="fas fa-users"></i> Signatures récentes (<?php echo count($signatures); ?>)</h3>
                    
                    <?php if (!empty($signatures)): ?>
                        <div class="signatures-container">
                            <?php foreach ($signatures as $sig): ?>
                                <div class="signature-item" data-id="<?php echo $sig['IDS']; ?>">
                                    <div class="avatar">
                                        <?php echo strtoupper(substr($sig['Prenom'], 0, 1)); ?>
                                    </div>
                                    <div class="signature-info">
                                        <div class="signature-name"><?php echo htmlspecialchars($sig['Prenom'] . ' ' . $sig['Nom']); ?></div>
                                        <div class="signature-details">
                                            <span class="signature-country"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($sig['Pays']); ?></span>
                                            <span class="signature-date"><i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($sig['Date'] . ' ' . $sig['Heure'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-signatures">
                            <i class="fas fa-signature empty-icon"></i>
                            <p>Soyez le premier à signer cette pétition !</p>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        <?php else: ?>
            <div class="error-state card fade-in">
                <i class="fas fa-exclamation-triangle"></i>
                <h2>Pétition introuvable</h2>
                <p>La pétition que vous recherchez n'existe pas ou a été supprimée.</p>
                <a href="liste_petition.php" class="btn btn-primary">Voir toutes les pétitions</a>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 PetitionHub - Tous droits réservés</p>
        </div>
    </footer>

    <script>
        // Add a function for animated signature count
        function animateCounterValues() {
            document.querySelectorAll('.counter-value').forEach(counter => {
                const finalValue = parseInt(counter.getAttribute('data-count'));
                const duration = 1500; // milliseconds
                const interval = 50;
                const increment = Math.max(1, Math.floor(finalValue / (duration / interval)));
                
                let currentValue = 0;
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        clearInterval(timer);
                        currentValue = finalValue;
                    }
                    counter.textContent = currentValue;
                }, interval);
            });
        }
        
        // Run the animation when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Add the counter animation
            animateCounterValues();
            
            // Add floating effect to avatar icons
            document.querySelectorAll('.avatar').forEach((avatar, index) => {
                avatar.style.animationDelay = `${0.1 * index}s`;
                avatar.classList.add('floating');
            });
            
            // Add shine effect to buttons
            document.querySelectorAll('.btn').forEach(btn => {
                btn.classList.add('shine-effect');
            });
            
            // Add an event listener for the form focus
            document.querySelectorAll('.form-group input, .form-group textarea').forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.form-group').classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (this.value === '') {
                        this.closest('.form-group').classList.remove('focused');
                    }
                });
            });
            
            // Function to handle form submission with AJAX
            const signatureForm = document.querySelector('.signature-form form');
            const idp = <?php echo $idp; ?>;
            let lastSignatureId = 0;
            
            // Get initial last signature ID (if any)
            const signatureItems = document.querySelectorAll('.signature-item');
            if (signatureItems.length > 0) {
                // Get IDs from data attributes (we added these above)
                const ids = Array.from(signatureItems)
                    .map(item => parseInt(item.dataset.id || 0))
                    .filter(id => !isNaN(id)); // Filter out NaN values
                
                if (ids.length > 0) {
                    lastSignatureId = Math.max(...ids);
                }
            }
            
            // Set up AJAX for form submission
            if (signatureForm) {
                signatureForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const xhr = new XMLHttpRequest();
                    
                    xhr.open('POST', this.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    
                    xhr.onload = function() {
                        if (this.status === 200) {
                            try {
                                const response = JSON.parse(this.responseText);
                                
                                if (response.success) {
                                    // Success - clear form and show success message
                                    signatureForm.reset();
                                    
                                    const successMessage = document.createElement('div');
                                    successMessage.className = 'alert alert-success fade-in';
                                    successMessage.innerHTML = '<i class="fas fa-check-circle"></i> Votre signature a été ajoutée avec succès !';
                                    
                                    signatureForm.prepend(successMessage);
                                    
                                    // Remove message after a few seconds
                                    setTimeout(() => {
                                        successMessage.remove();
                                    }, 5000);
                                    
                                    // Refresh signatures immediately
                                    refreshSignatures(true);
                                } else if (response.errors) {
                                    // Display validation errors
                                    const errorMessage = document.createElement('div');
                                    errorMessage.className = 'alert alert-error fade-in';
                                    errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + response.errors.join('<br>');
                                    
                                    signatureForm.prepend(errorMessage);
                                    
                                    // Remove message after a few seconds
                                    setTimeout(() => {
                                        errorMessage.remove();
                                    }, 5000);
                                }
                            } catch (e) {
                                console.error('Error parsing JSON response:', e);
                            }
                        }
                    };
                    
                    xhr.send(formData);
                });
            }
            
            // Function to refresh signatures
            function refreshSignatures(animateNew = false) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `../Traitement/recent_signatures.php?id=${idp}&last_id=${lastSignatureId}&format=json`, true);
                
                xhr.onload = function() {
                    if (this.status === 200) {
                        try {
                            const response = JSON.parse(this.responseText);
                            const newSignatures = response.signatures;
                            
                            if (newSignatures && newSignatures.length > 0) {
                                // Update lastSignatureId
                                const newIds = newSignatures.map(s => parseInt(s.IDS));
                                const newLastId = Math.max(...newIds);
                                
                                if (newLastId > lastSignatureId) {
                                    lastSignatureId = newLastId;
                                    
                                    // Refresh the entire signature list
                                    fetchAllSignatures(animateNew);
                                }
                            }
                        } catch (e) {
                            console.error('Error parsing JSON response:', e);
                        }
                    }
                };
                
                xhr.send();
            }
            
            // Function to fetch all signatures
            function fetchAllSignatures(animateNew = false) {
                const signaturesContainer = document.querySelector('.signatures-container');
                const emptySignatures = document.querySelector('.empty-signatures');
                const signatureList = document.querySelector('.signature-list');
                
                if (signaturesContainer || emptySignatures) {
                    const tempXhr = new XMLHttpRequest();
                    tempXhr.open('GET', `../Traitement/recent_signatures.php?id=${idp}&limit=5`, true);
                    
                    tempXhr.onload = function() {
                        if (this.status === 200) {
                            // Get the current count from the server
                            const countXhr = new XMLHttpRequest();
                            countXhr.open('GET', `../Traitement/get_signature_count.php?id=${idp}`, true);
                            
                            countXhr.onload = function() {
                                if (this.status === 200) {
                                    try {
                                        const countResponse = JSON.parse(this.responseText);
                                        const totalSignatures = countResponse.count;
                                        
                                        // Update the heading
                                        const signatureCountElement = document.querySelector('.signature-list h3');
                                        if (signatureCountElement) {
                                            signatureCountElement.innerHTML = `<i class="fas fa-users"></i> Signatures récentes (${totalSignatures})`;
                                        }
                                        
                                        // Create or update the signatures container
                                        if (signaturesContainer) {
                                            signaturesContainer.innerHTML = tempXhr.responseText;
                                            
                                            // Animate if requested
                                            if (animateNew) {
                                                signaturesContainer.classList.add('highlight-new');
                                                setTimeout(() => {
                                                    signaturesContainer.classList.remove('highlight-new');
                                                }, 3000);
                                            }
                                        } else if (emptySignatures && tempXhr.responseText.trim() !== '') {
                                            // Replace empty state with signatures
                                            const newContainer = document.createElement('div');
                                            newContainer.className = 'signatures-container';
                                            newContainer.innerHTML = tempXhr.responseText;
                                            
                                            emptySignatures.replaceWith(newContainer);
                                            
                                            if (animateNew) {
                                                newContainer.classList.add('highlight-new');
                                                setTimeout(() => {
                                                    newContainer.classList.remove('highlight-new');
                                                }, 3000);
                                            }
                                        }
                                    } catch (e) {
                                        console.error('Error parsing count JSON:', e);
                                    }
                                }
                            };
                            
                            countXhr.send();
                        }
                    };
                    
                    tempXhr.send();
                }
            }
            
            // Check for new signatures every 10 seconds
            setInterval(refreshSignatures, 10000);
        });
    </script>

    <style>
        /* Enhanced styles for the signature page */
        .petition-detail {
            margin-bottom: 2rem;
            background: linear-gradient(135deg, #fff, rgba(171, 186, 124, 0.1));
            border-left: 5px solid var(--accent-color);
            position: relative;
            overflow: hidden;
        }
        
        .petition-detail::before {
            content: '';
            position: absolute;
            top: -10px;
            right: -10px;
            width: 60px;
            height: 60px;
            background-color: var(--accent-bright);
            opacity: 0.2;
            border-radius: 50%;
            z-index: 0;
        }
        
        .petition-detail h2 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
            position: relative;
            z-index: 1;
        }
        
        .petition-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-light);
            position: relative;
            z-index: 1;
        }
        
        .petition-meta span i {
            color: var(--accent-color);
            margin-right: 0.5rem;
        }
        
        .petition-description {
            line-height: 1.8;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        /* Grid and cards */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        /* Signature form styles */
        .signature-form h3, .signature-list h3 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: var(--primary-dark);
            position: relative;
        }
        
        .signature-form h3::after, .signature-list h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            height: 3px;
            width: 40px;
            background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
            border-radius: 10px;
        }
        
        .form-intro {
            margin-bottom: 1.5rem;
            color: var(--text-light);
        }
        
        /* Form focus effect */
        .form-group.focused label {
            color: var(--accent-color);
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        
        /* Signature list animations */
        .signatures-container {
            max-height: 500px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) #f0f0f0;
        }
        
        .signatures-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .signatures-container::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 10px;
        }
        
        .signatures-container::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 10px;
        }
        
        .signature-item {
            display: flex;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }
        
        .signature-item:hover {
            transform: translateX(5px);
            background-color: rgba(255, 227, 26, 0.1);
            padding-left: 10px;
            border-radius: 8px;
        }
        
        .signature-item:last-child {
            border-bottom: none;
        }
        
        /* Animated avatar */
        .avatar {
            width: 40px;
            height: 40px;
            background-color: var(--accent-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(240, 147, 25, 0.3);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        
        .signature-info {
            flex: 1;
        }
        
        .signature-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--primary-dark);
        }
        
        .signature-details {
            display: flex;
            gap: 1rem;
            font-size: 0.85rem;
            color: var(--text-light);
        }
        
        .signature-country i, .signature-date i {
            color: var(--accent-color);
        }
        
        /* Empty state and error state */
        .empty-signatures, .error-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-icon, .error-state i {
            font-size: 3rem;
            color: var(--accent-bright);
            margin-bottom: 1rem;
            opacity: 0.7;
            animation: pulse 2s infinite;
        }
        
        .error-state i {
            color: var(--accent-color);
        }
        
        .error-state h2 {
            margin-bottom: 1rem;
            color: var(--primary-dark);
        }
        
        .error-state p {
            margin-bottom: 2rem;
            color: var(--text-light);
        }
        
        /* Shine effect for buttons */
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
            animation: shine 4s infinite;
        }
        
        @keyframes shine {
            0% { transform: rotate(30deg) translateX(-300%); }
            50% { transform: rotate(30deg) translateX(0%); }
            100% { transform: rotate(30deg) translateX(300%); }
        }
        
        /* Pulse animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Responsive styles */
        @media screen and (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .petition-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>

    <!-- Add animated counter to the signature count -->
    <script>
        // Find and replace the signature count with an animated counter
        document.addEventListener('DOMContentLoaded', function() {
            const signatureCountElement = document.querySelector('.petition-meta span:nth-child(2)');
            if (signatureCountElement) {
                const countText = signatureCountElement.innerHTML;
                const countMatch = countText.match(/(\d+)/);
                
                if (countMatch) {
                    const count = countMatch[1];
                    signatureCountElement.innerHTML = `<i class="fas fa-signature"></i> <span class="counter-value" data-count="${count}">0</span> signatures`;
                }
            }
        });
    </script>
</body>
</html>
