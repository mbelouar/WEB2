<?php
session_start();
if (!isset($_SESSION['client'])) {
    header("Location: ../client/connexion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Réclamations - Espace Client</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Mon Espace</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data
