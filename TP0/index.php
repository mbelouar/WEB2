<?php include_once 'functions.php'; ?>
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
        <!-- <div class="content">
            <h2>Add Email</h2>
            <?php include 'process.php'; ?>
            <form action="" method="post">
                <input type="email" name="email" placeholder="Enter a valid email" required>
                <button type="submit" name="add_email">Add</button>
            </form>
        </div> -->

        <div class="content">
            <h2>Upload File</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                Select File to Upload
                <input type="file" name="file" required>
                <button type="submit" name="upload">Upload</button>
            </form>
            <?php include 'upload.php'; ?>
        </div>
        <div class="content"></div>
            <h2>Choose Action</h2>
            <form action="" method="post">
                <button type="submit" name="action" value="add_email">Add Email</button>
                <button type="submit" name="action" value="valid_emails">Valid Emails</button>
                <button type="submit" name="action" value="invalid_emails">Invalid Emails</button>
            </form>
        </div>
        <?php include 'clean_emails.php'; ?>
        <?php include 'actions.php'; ?>
        
        <!-- <div class="content">
            <h2>Email List & Frequency</h2>
            <?php include 'display.php'; ?>
        </div> -->

    </div>
</body>
</html>
