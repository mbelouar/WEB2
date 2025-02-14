<?php 
include_once 'functions.php'; 
session_start();
?>


<link rel="stylesheet" href="style.css">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emails Manager</title>
</head>
<body>

    <div class="container">
        <h1>Emails Manager</h1>

        <div class="content">
            <h2>Upload File</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                Select File to Upload
                <input type="file" name="uploadedFile" required>
                <button type="submit" name="upload">Upload</button>
            </form>
        </div>
        <?php if (isset($_SESSION["message"])): ?>
            <p style="color: <?= ($_SESSION["msg_type"] == "success") ? 'green' : 'red'; ?>; font-weight: bold;">
                <?= $_SESSION["message"]; ?>
            </p>
            <?php unset($_SESSION["message"], $_SESSION["msg_type"]); // Clear message after displaying ?>
        <?php endif; ?>

        
        <?php include 'actions.php'; ?>

    </div>
</body>
</html>
