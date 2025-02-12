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
        <div class="content">
            <h2>Add Email</h2>
            <?php include 'process.php'; ?>
            <form action="" method="post">
                <input type="email" name="email" placeholder="Enter a valid email" required>
                <button type="submit" name="add_email">Add</button>
            </form>
        </div>
        <?php include 'clean_emails.php'; ?>
        <div class="content">
            <h2>Email List & Frequency</h2>
            <?php include 'display.php'; ?>
        </div>

    </div>
</body>
</html>
