<?php
// Start session
session_start();

// Clear all CV data from session
unset($_SESSION['cv_data']);
unset($_SESSION['edit_mode']);

// Redirect to the form page
header('Location: ../../formulaire.php');
exit();
?> 