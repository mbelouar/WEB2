<?php
session_start();
include "getInfo.php";
include "handle_upload.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="valide.css">
    <link rel="stylesheet" href="confirm.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
    <script>
        console.log(JSON.parse('<?php echo json_encode($_SESSION['cv_data']); ?>'));
    </script>
    <div id="particles-js"></div>
    <div class="container">
        <div class="checkmark-container">
            <svg class="checkmark" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>

        <div class="success-message">
            <h1>Données enregistrées avec succès!</h1>
            <p>Merci, <?php echo $firstname . " " . $lastname; ?>. Vos informations ont été sauvegardées.</p>
        </div>

        <div class="button-container">
            <a href="formulaire.php">
                <button class="button modifier-button">Modifier</button>
            </a>
            <button class="button generator-button" id="generatePdf">Générer</button>
        </div>
    </div>

    <script src="particles.js"></script>
    <script src="generatePdf.js"></script>
    <script src="confirm.js"></script>
    <script>
        particlesJS.load('particles-js', 'assets/particles.json', function() {
            console.log('callback - particles.js config loaded');
        });
    </script>
</body>
</html>
