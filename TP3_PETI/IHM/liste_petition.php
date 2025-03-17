<?php
require '../BD/connexion.php';  // Connexion à la base de données

// Récupérer la pétition la plus signée
$queryTop = "SELECT p.IDP, p.Titre, COUNT(*) AS nb_signatures, p.Description
             FROM Signature s
             JOIN Petition p ON s.IDP = p.IDP
             GROUP BY s.IDP
             ORDER BY nb_signatures DESC
             LIMIT 1";
$resultTop = $pdo->query($queryTop);
$petitionTop = $resultTop->fetch(PDO::FETCH_ASSOC);

// Récupérer toutes les pétitions avec le nombre de signatures
$queryPetitions = "SELECT p.*, 
                  (SELECT COUNT(*) FROM Signature s WHERE s.IDP = p.IDP) AS nb_signatures
                  FROM Petition p
                  ORDER BY DatePublic DESC";
$resultPetitions = $pdo->query($queryPetitions);
$petitions = $resultPetitions->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Découvrez les Pétitions | PetitionHub</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><i class="fas fa-pen-fancy"></i> PetitionHub</h1>
            <p class="tagline">Découvrez des causes qui méritent votre soutien</p>
            <div class="nav-links">
                <a href="index.php"><i class="fas fa-home"></i> Accueil</a>
                <a href="ajouter_petition.php"><i class="fas fa-plus-circle"></i> Créer une pétition</a>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="page-header fade-in">
            <h2>Pétitions actives</h2>
            <p>Découvrez les pétitions en cours et apportez votre soutien avec votre signature</p>
        </section>

        <?php if ($petitionTop): ?>
        <section class="top-petition card fade-in">
            <div class="top-petition-badge">
                <i class="fas fa-award"></i> La plus populaire
            </div>
            <div class="top-petition-content">
                <h3><?php echo htmlspecialchars($petitionTop['Titre']); ?></h3>
                <p class="petition-description"><?php echo nl2br(htmlspecialchars(substr($petitionTop['Description'], 0, 200))); ?><?php if (strlen($petitionTop['Description']) > 200) echo '...'; ?></p>
                <div class="signature-count">
                    <i class="fas fa-signature"></i> 
                    <span class="count"><?php echo $petitionTop['nb_signatures']; ?></span> 
                    <span class="count-label">signatures</span>
                </div>
                <a href="signature.php?id=<?php echo $petitionTop['IDP']; ?>" class="btn btn-primary">
                    <i class="fas fa-pen-nib"></i> Signer cette pétition
                </a>
            </div>
        </section>
        <?php endif; ?>

        <section class="petitions-grid fade-in">
            <?php foreach ($petitions as $petition): ?>
                <?php if (!$petitionTop || $petition['IDP'] != $petitionTop['IDP']): ?>
                <div class="petition-card card">
                    <div class="petition-header">
                        <h3><?php echo htmlspecialchars($petition['Titre']); ?></h3>
                        <div class="petition-meta">
                            <span class="petition-date">
                                <i class="far fa-calendar-alt"></i> Publiée le <?php echo date('d/m/Y', strtotime($petition['DatePublic'])); ?>
                            </span>
                            <span class="petition-deadline">
                                <i class="far fa-clock"></i> Jusqu'au <?php echo date('d/m/Y', strtotime($petition['DateFinP'])); ?>
                            </span>
                        </div>
                    </div>
                    <p class="petition-description"><?php echo nl2br(htmlspecialchars(substr($petition['Description'], 0, 150))); ?><?php if (strlen($petition['Description']) > 150) echo '...'; ?></p>
                    <div class="petition-footer">
                        <div class="signature-count">
                            <i class="fas fa-signature"></i> 
                            <span class="count"><?php echo $petition['nb_signatures']; ?></span>
                            <span class="count-label">signatures</span>
                        </div>
                        <a href="signature.php?id=<?php echo $petition['IDP']; ?>" class="btn">
                            <i class="fas fa-pen-nib"></i> Signer
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </section>

        <?php if (empty($petitions)): ?>
        <div class="empty-state card fade-in">
            <i class="fas fa-scroll empty-icon"></i>
            <h3>Aucune pétition disponible pour le moment</h3>
            <p>Soyez le premier à lancer une pétition pour une cause qui vous tient à cœur.</p>
            <a href="ajouter_petition.php" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Créer une pétition
            </a>
        </div>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 PetitionHub - Tous droits réservés</p>
        </div>
    </footer>

    <script>
        // Function to fetch recent petitions
        let lastPetitionId = <?php echo !empty($petitions) ? max(array_column($petitions, 'IDP')) : 0; ?>;
        
        function checkForNewPetitions() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../Traitement/recent_petitions.php?last_id=' + lastPetitionId, true);
            
            xhr.onload = function() {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    const newPetitions = response.petitions;
                    
                    if (newPetitions.length > 0) {
                        // Update lastPetitionId
                        lastPetitionId = Math.max(...newPetitions.map(p => parseInt(p.IDP)));
                        
                        // Create notification
                        const notification = document.createElement('div');
                        notification.className = 'new-petitions-notification fade-in';
                        notification.innerHTML = `
                            <i class="fas fa-bell"></i>
                            ${newPetitions.length} nouvelle${newPetitions.length > 1 ? 's' : ''} pétition${newPetitions.length > 1 ? 's' : ''} disponible${newPetitions.length > 1 ? 's' : ''}
                            <button class="btn btn-sm">Afficher</button>
                        `;
                        document.querySelector('.page-header').after(notification);
                        
                        // Add click event to show new petitions
                        notification.querySelector('button').addEventListener('click', function() {
                            const petitionsGrid = document.querySelector('.petitions-grid');
                            
                            newPetitions.forEach(petition => {
                                const petitionCard = createPetitionCard(petition);
                                petitionsGrid.prepend(petitionCard);
                                
                                // Add animation class
                                setTimeout(() => {
                                    petitionCard.classList.add('highlight-new');
                                    setTimeout(() => {
                                        petitionCard.classList.remove('highlight-new');
                                    }, 3000);
                                }, 100);
                            });
                            
                            // Remove notification
                            notification.remove();
                        });
                    }
                }
            };
            
            xhr.send();
        }
        
        // Create petition card HTML
        function createPetitionCard(petition) {
            const card = document.createElement('div');
            card.className = 'petition-card card fade-in';
            
            const html = `
                <div class="petition-header">
                    <h3>${petition.Titre}</h3>
                    <div class="petition-meta">
                        <span class="petition-date">
                            <i class="far fa-calendar-alt"></i> Publiée le ${formatDate(petition.DatePublic)}
                        </span>
                        <span class="petition-deadline">
                            <i class="far fa-clock"></i> Jusqu'au ${formatDate(petition.DateFinP)}
                        </span>
                    </div>
                </div>
                <p class="petition-description">${petition.Description.length > 150 ? petition.Description.substring(0, 150) + '...' : petition.Description}</p>
                <div class="petition-footer">
                    <div class="signature-count">
                        <i class="fas fa-signature"></i> 
                        <span class="count">${petition.nb_signatures || 0}</span>
                        <span class="count-label">signatures</span>
                    </div>
                    <a href="signature.php?id=${petition.IDP}" class="btn">
                        <i class="fas fa-pen-nib"></i> Signer
                    </a>
                </div>
            `;
            
            card.innerHTML = html;
            return card;
        }
        
        // Format date
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('fr-FR');
        }
        
        // Check for new petitions every 30 seconds
        setInterval(checkForNewPetitions, 30000);

        // Add code to periodically update the top petition
        function updateTopPetition() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../Traitement/top_petition.php?format=json', true);
            
            xhr.onload = function() {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.petition) {
                        // Update the top petition section if it exists and has changed
                        const currentTopPetition = document.querySelector('.top-petition');
                        if (currentTopPetition) {
                            const topPetitionId = currentTopPetition.getAttribute('data-id');
                            if (topPetitionId != response.petition.IDP || 
                                parseInt(document.querySelector('.progress-count').textContent) != response.petition.signature_count) {
                                
                                // Reload the entire top petition section
                                const tempXhr = new XMLHttpRequest();
                                tempXhr.open('GET', '../Traitement/top_petition.php', true);
                                tempXhr.onload = function() {
                                    if (this.status === 200) {
                                        currentTopPetition.innerHTML = this.responseText;
                                        
                                        // Add highlight effect
                                        currentTopPetition.classList.add('highlight-update');
                                        setTimeout(() => {
                                            currentTopPetition.classList.remove('highlight-update');
                                        }, 3000);
                                    }
                                };
                                tempXhr.send();
                            }
                        }
                    }
                }
            };
            
            xhr.send();
        }
        
        // Check for updates to top petition every minute
        setInterval(updateTopPetition, 60000);
    </script>

    <style>
        /* Additional styles for the petition list */
        .page-header {
            text-align: center;
            margin: 2rem 0 3rem;
        }
        
        .page-header p {
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .top-petition {
            position: relative;
            padding: 2rem;
            margin-bottom: 3rem;
            overflow: hidden;
            background: linear-gradient(135deg, #f0f7ff, #e5f1ff);
            border-left: 6px solid var(--primary-color);
        }
        
        .top-petition-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-bottom-left-radius: var(--border-radius);
        }
        
        .top-petition-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .top-petition h3 {
            font-size: 1.8rem;
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
        }
        
        .signature-count {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            color: var(--text-color);
        }
        
        .signature-count i {
            color: var(--accent-color);
        }
        
        .count {
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .count-label {
            color: var(--text-light);
        }
        
        .petitions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .petition-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: var(--transition);
        }
        
        .petition-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .petition-header {
            margin-bottom: 1rem;
        }
        
        .petition-header h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: var(--primary-dark);
        }
        
        .petition-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }
        
        .petition-description {
            flex: 1;
            margin-bottom: 1.5rem;
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .petition-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            margin-bottom: 3rem;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: var(--text-light);
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
        }
        
        .empty-state p {
            max-width: 500px;
            margin: 0 auto 2rem;
            color: var(--text-light);
        }
        
        @media screen and (max-width: 768px) {
            .petitions-grid {
                grid-template-columns: 1fr;
            }
            
            .petition-meta {
                flex-direction: column;
                gap: 0.25rem;
            }
        }
        
        /* New petition notification */
        .new-petitions-notification {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideDown 0.5s ease;
        }
        
        .new-petitions-notification i {
            margin-right: 0.5rem;
        }
        
        .highlight-new {
            animation: highlightNew 3s ease;
        }
        
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes highlightNew {
            0% { box-shadow: 0 0 0 2px var(--accent-color); }
            70% { box-shadow: 0 0 0 2px var(--accent-color); }
            100% { box-shadow: none; }
        }
        
        /* Top petition styles */
        .signature-progress {
            margin: 1.5rem 0;
        }
        
        .progress-bar {
            height: 12px;
            background: #e9ecef;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, var(--accent-color), var(--primary-color));
            border-radius: 6px;
            transition: width 0.5s ease;
        }
        
        .progress-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }
        
        .progress-count {
            font-weight: 600;
        }
        
        .progress-target {
            color: var(--text-light);
        }
        
        .petition-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .stat {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-light);
        }
        
        .stat i {
            color: var(--primary-color);
        }
        
        .highlight-update {
            animation: highlightUpdate 3s ease;
        }
        
        @keyframes highlightUpdate {
            0% { background-color: rgba(67, 97, 238, 0.1); }
            70% { background-color: rgba(67, 97, 238, 0.1); }
            100% { background-color: transparent; }
        }
    </style>
</body>
</html>
