<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion d'Électricité</title>
    <link rel="stylesheet" href="../../style.css"> <!-- Adaptez le chemin si nécessaire -->
</head>
<body>
<header>
    <h1>Gestion d'Électricité</h1>
    <nav>
        <ul>
            <?php if (isset($_SESSION['client'])): ?>
                <li><a href="../../traitement/clientTraitement.php">Accueil</a></li>
                <li><a href="../../traitement/factureTraitement.php?action=list">Mes Factures</a></li>
                <li><a href="../../traitement/reclamationTraitement.php?action=list">Mes Réclamations</a></li>
                <li><a href="../../traitement/notificationTraitement.php?action=list">Mes Notifications</a></li>
                <li><a href="../../traitement/clientTraitement.php?action=logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="inscription.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<hr>
