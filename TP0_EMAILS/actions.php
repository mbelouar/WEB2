<?php
include_once 'functions.php';
include_once 'clean_emails.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        $action = $_POST["add"];
    } elseif (isset($_POST["valid"])) {
        $action = $_POST["valid"];
    } elseif (isset($_POST["invalid"])) {
        $action = $_POST["invalid"];
    } elseif (isset($_POST["duplicate"])) {
        $action = $_POST["duplicate"];
    } elseif (isset($_POST["domain"])) {
        $action = $_POST["domain"];
    }
}
?>

<div class="content">
    <h2>Choose Action</h2>
    <form action="" method="post">
        <button type="submit" name="add" value="add_email">Add Email</button>
        <button type="submit" name="valid" value="valid_emails">Valid Emails</button>
        <button type="submit" name="invalid" value="invalid_emails">Invalid Emails</button>
        <button type="submit" name="duplicate" value="duplicate_emails">Duplicate Emails</button>
        <button type="submit" name="domain" value="domain_emails">Domain Emails</button>
    </form>
    
</div>

<?php
    $fileName = isset($_SESSION["uploadedFile"]) ? $_SESSION["uploadedFile"] : '';
    $file = "uploads/" . $fileName;

    // echo "<p>File: $file</p>";
    // echo "<p>Action: $action</p>";
?>

<?php 
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
        ?>
        <div class="content">
            <h2>Valid Emails</h2>
            <!-- Logic to display valid emails -->
            <?php displayValidEmails($file); ?>
        </div>
        <?php
    } elseif ($action == "invalid_emails") {
        ?>
        <div class="content">
            <h2>Invalid Emails</h2>
            <!-- Logic to display invalid emails -->
            <?php displayInvalidEmails($file); ?>
        </div>
        <?php
    } elseif ($action == "duplicate_emails") {
        ?>
        <div class="content">
            <h2>Duplicate Emails</h2>
            <!-- Logic to display duplicate emails -->
            <?php displayDuplicateEmails($file); ?>
        </div>
        <?php
    } elseif ($action == "domain_emails") {
        ?>
        <div class="content">
            <h2>Domain Emails</h2>
            <!-- Logic to display domain emails -->
            <?php displayDomainEmails($file); ?>
        </div>
        <?php
    }
}
?>
