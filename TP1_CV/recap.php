<?php
session_start();
include "getInfo.php";
include "handle_upload.php";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if we're in edit mode
$isEditMode = isset($_POST['edit_mode']) && $_POST['edit_mode'] == '1';
$userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;

// Debug log
error_log('Recap.php - Edit Mode: ' . ($isEditMode ? 'YES' : 'NO') . ', User ID: ' . $userId);

// Save form data to session
$_SESSION['cv_data'] = $_POST;

// Add edit mode flag if we're in edit mode
if ($isEditMode && $userId) {
    $_SESSION['edit_mode'] = true;
    $_SESSION['cv_data']['user_id'] = $userId;
    $_SESSION['preserve_edit_mode'] = true; // This flag prevents clearing edit mode on direct access
    
    error_log('Set edit mode in session. User ID: ' . $userId);
} else {
    // For new submissions, we need to get the user ID from the database
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $_SESSION['edit_mode'] = true;
        $_SESSION['cv_data']['user_id'] = $row['id'];
        $_SESSION['preserve_edit_mode'] = true;
        error_log('Set edit mode for new submission. User ID: ' . $row['id']);
    }
    $stmt->close();
    $conn->close();
}

// Process file upload if present
if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
    $filename = $_FILES["profile_picture"]["name"];
    $filetype = $_FILES["profile_picture"]["type"];
    $filesize = $_FILES["profile_picture"]["size"];
    
    // Verify file extension
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if(!array_key_exists($ext, $allowed)) {
        $_SESSION['message'] = "Erreur: Veuillez sélectionner un format de fichier valide.";
        $_SESSION['message_type'] = "danger";
        header("Location: formulaire.php");
        exit();
    }
    
    // Verify file size - 5MB maximum
    $maxsize = 5 * 1024 * 1024;
    if($filesize > $maxsize) {
        $_SESSION['message'] = "Erreur: La taille du fichier est supérieure à la limite autorisée (5MB).";
        $_SESSION['message_type'] = "danger";
        header("Location: formulaire.php");
        exit();
    }
    
    // Verify MIME type of the file
    if(in_array($filetype, $allowed)) {
        // Check if file exists before uploading
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Generate unique filename to prevent overwriting
        $new_filename = uniqid() . "." . $ext;
        $target_file = $target_dir . $new_filename;
        
        // Move the uploaded file
        if(move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $_SESSION['cv_data']['picture'] = $target_file;
        } else {
            $_SESSION['message'] = "Erreur: Il y a eu un problème lors du téléchargement de votre fichier.";
            $_SESSION['message_type'] = "danger";
            header("Location: formulaire.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Erreur: Il y a eu un problème lors du téléchargement de votre fichier.";
        $_SESSION['message_type'] = "danger";
        header("Location: formulaire.php");
        exit();
    }
}
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
            <h1>Données <?php echo $isEditMode ? 'modifiées' : 'enregistrées'; ?> avec succès!</h1>
            <p>Merci, <?php echo $firstname . " " . $lastname; ?>. Vos informations ont été sauvegardées.</p>
        </div>

        <div class="button-container">
            <a href="formulaire.php">
                <button class="button modifier-button">Modifier</button>
            </a>
            <button class="button generator-button" id="generatePdf">Générer</button>
            <?php if ($isEditMode): ?>
            <a href="ADMIN/index.php">
                <button class="button admin-button">Retour à l'Administration</button>
            </a>
            <?php endif; ?>
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
