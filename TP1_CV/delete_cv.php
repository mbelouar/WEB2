<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database functions
require_once 'getInfo.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ADMIN/index.php');
    exit();
}

$userId = $_GET['id'];

// Delete CV from database
function deleteCV($userId) {
    $conn = connectDB();
    $success = false;
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Delete user (cascading will delete related data)
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            $success = true;
        }
        
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        
    } catch (Exception $e) {
        // Roll back in case of error
        $conn->rollback();
        error_log("Error deleting CV: " . $e->getMessage());
    } finally {
        $conn->close();
    }
    
    return $success;
}

// Perform deletion
$deleted = deleteCV($userId);

// Set success/error message
if ($deleted) {
    $_SESSION['message'] = "Le CV a été supprimé avec succès.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Erreur lors de la suppression du CV.";
    $_SESSION['message_type'] = "danger";
}

// Redirect back to admin
header('Location: ADMIN/index.php');
exit();
?> 