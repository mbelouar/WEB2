<?php
require '../BD/signature.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate data
    $idp = isset($_POST['idp']) ? intval($_POST['idp']) : 0;
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $pays = isset($_POST['pays']) ? trim($_POST['pays']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    $errors = [];
    
    // Basic validation
    if (empty($idp)) $errors[] = "ID de pétition invalide";
    if (empty($nom)) $errors[] = "Le nom est obligatoire";
    if (empty($prenom)) $errors[] = "Le prénom est obligatoire";
    if (empty($pays)) $errors[] = "Le pays est obligatoire";
    if (empty($email)) $errors[] = "L'email est obligatoire";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format d'email invalide";
    
    // Process if no errors
    if (empty($errors)) {
        // Add signature with current date and time
        ajouterSignature($idp, $nom, $prenom, $pays, date('Y-m-d'), date('H:i:s'), $email);
        
        // For AJAX requests, return success message
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Signature ajoutée avec succès']);
            exit;
        }
        
        // For normal form submissions, redirect back to the petition page
        header('Location: ../IHM/signature.php?id=' . $idp);
        exit;
    } else {
        // Return errors for AJAX requests
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }
        
        // Store errors in session and redirect for regular form submissions
        session_start();
        $_SESSION['signature_errors'] = $errors;
        header('Location: ../IHM/signature.php?id=' . $idp);
        exit;
    }
}

// For direct access without POST data
header('Location: ../IHM/liste_petition.php');
exit;
?>
