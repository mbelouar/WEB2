<?php 
include_once 'functions.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Emails Manager</title>
</head>
<body>

    <div class="container">
        <header>
            <h1>Emails Manager</h1>
        </header>

        <div class="content">
            <h2>Upload File</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                Select File to Upload
                <input type="file" name="file" required>
                <button type="submit" name="upload">Upload</button>
            </form>
        </div>
        <?php displayMessage(); ?>
        <?php include 'actions.php'; ?>

    </div>
</body>
</html>
