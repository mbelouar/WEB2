<?php
// BD/Fournisseur.php

class Fournisseur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($emailF, $passwordF) {
        $sql = "SELECT * FROM Fournisseur WHERE emailF = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$emailF]);
        $fournisseur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fournisseur && password_verify($passwordF, $fournisseur['passwordF'])) {
            return $fournisseur;
        }
        return false;
    }

    // Autres méthodes CRUD (create, read, update, delete)...
}
?>
