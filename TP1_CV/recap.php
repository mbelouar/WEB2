<?php
session_start();
include "handle_upload.php";
include "getInfo.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="valide.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</head>
<body>

    <div id="particles-js"></div>
    <div class="content-wrapper">

        <div class="success-message">
            <i class="fas fa-check-circle" style="font-size: 30px; margin-right: 10px;"></i>
            <h2>Données enregistrées avec succès!</h2>
            <div>
                <p>Merci, <?php echo $firstname . " " . $lastname; ?>. Vos informations ont été sauvegardées.</p>
            </div>
        </div>

        <div class="form-controls">
            <a href="formulaire.php">
                <button type="button">Modifier</button>
            </a>
            <button type="button" id="generatePdf">Générer</button>
        </div>

    </div>
    <script src="particles.js"></script>
    <script src="generatePdf.js"></script>
    <script>
        particlesJS.load('particles-js', 'assets/particles.json', function() {
            console.log('callback - particles.js config loaded');
        });
    </script>
</body>
</html>
