ct<?php
// This is a test script to simulate a successful login
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once 'BD/db.php';

// Set a test client session
$_SESSION['client'] = [
    'id' => 1,
    'cin' => 'CIN001',
    'nom' => 'Krichi',
    'prenom' => 'Sara',
    'email' => 'krichi.2003.sara@gmail.com',
    'telephone' => '0610069524',
    'adresse' => 'adress1'
];

// Debugging information
echo '<h3>Session Details</h3>';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

echo '<p>Session created successfully. You are now logged in as ' . $_SESSION['client']['nom'] . ' ' . $_SESSION['client']['prenom'] . '.</p>';

// Use a direct link with proper path to avoid redirection issues
$absolute_path = 'http://localhost:8080/IHM/client/client_new_consumption.php';
echo '<p>Now try accessing the consumption page: <a href="' . $absolute_path . '">Go to consumption page</a></p>';

// Alternative links to try
echo '<p>Alternative links:</p>';
echo '<ul>';
echo '<li><a href="IHM/client/client_dashboard.php">Dashboard</a></li>';
echo '<li><a href="IHM/client/client_invoices.php">Invoices</a></li>';
echo '<li><a href="IHM/client/client_complaint.php">Complaints</a></li>';
echo '</ul>';
?>
