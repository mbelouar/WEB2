<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = "";
    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            $data .= ucfirst($key) . ": " . implode(", ", $value) . "\n";
        } else {
            $data .= ucfirst($key) . ": " . htmlspecialchars($value) . "\n";
        }
    }

    // Save data to a file
    $filename = "data.txt";
    file_put_contents($filename, $data . "\n", FILE_APPEND);

    echo "Données enregistrées avec succès dans $filename.";
} else {
    echo "Aucune donnée reçue.";
}
?>
