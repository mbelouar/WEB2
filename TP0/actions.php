<?php
include_once 'functions.php';
include_once 'clean_emails.php';
// Start the session if you want to store messages
session_start();

// Handle actions based on button pressed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        $action = $_POST["add"];
    } elseif (isset($_POST["valid"])) {
        $action = $_POST["valid"];
    } elseif (isset($_POST["invalid"])) {
        $action = $_POST["invalid"];
    }
}
?>

<div class="content">
    <h2>Choose Action</h2>
    <!-- Form to choose action -->
    <form action="" method="post">
        <button type="submit" name="add" value="add_email">Add Email</button>
        <button type="submit" name="valid" value="valid_emails">Valid Emails</button>
        <button type="submit" name="invalid" value="invalid_emails">Invalid Emails</button>
    </form>
    
</div>

<?php
    $fileName = isset($_SESSION["uploadedFile"]) ? $_SESSION["uploadedFile"] : '';
    $file = "uploads/" . $fileName;
?>

<?php 
// Show the appropriate content based on the action chosen
if (isset($action)) {
    if ($action == "add_email") {
        // Show Add Email form
        ?>
        <div class="content">
            <h2>Add Email</h2>
            <form action="process.php" method="post">
                <input type="email" name="email" placeholder="Enter a valid email" required>
                <button type="submit" name="add_email">Add</button>
            </form>
        </div>
        
        <?php
    } elseif ($action == "valid_emails") {
        // Show Valid Emails content (you would typically include code to list valid emails)
        ?>
        <div class="content">
            <h2>Valid Emails</h2>
            <!-- Logic to display valid emails -->
            <?php displayValidEmails($file); ?>
        </div>
        <?php
    } elseif ($action == "invalid_emails") {
        // Show Invalid Emails content (you would typically include code to list invalid emails)
        ?>
        <div class="content">
            <h2>Invalid Emails</h2>
            <!-- Logic to display invalid emails -->
            <?php displayInvalidEmails($file); ?>
        </div>
        <?php
    }
}
?>
