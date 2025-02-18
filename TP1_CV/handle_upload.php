<?php

$error_message = "";

if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["picture"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $error_message = "Le fichier n'est pas une image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["picture"]["size"] > 500000) {
        $error_message = "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $error_message = ", seuls les fichiers JPG, JDésoléPEG, PNG sont autorisés.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $error_message = "Désolé, votre fichier n'a pas été téléchargé.";
        include "formulaire.php";
        exit();
    } else {
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
            echo "Le fichier " . htmlspecialchars(basename($_FILES["picture"]["name"])) . " a été téléchargé.";
        } else {
            $error_message = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            include "formulaire.php";
            exit();
        }
    }
}

?>