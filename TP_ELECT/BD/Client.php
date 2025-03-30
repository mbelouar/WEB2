<?php
// BD/Client.php

class Client {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Inscription d'un client avec vérification de l'email
    public function register($data) {
        // Vérifier si l'email existe déjà
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as nb FROM Client WHERE email = ?");
        $stmt->execute([$data['email']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['nb'] > 0) {
            // L'email existe déjà, on retourne false pour signaler l'erreur
            return false;
        }

        // Insertion du client si l'email n'existe pas
        $sql = "INSERT INTO Client (cin, nom, prenom, email, telephone, adresse, password)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['cin'],
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['telephone'],
            $data['adresse'],
            password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }
    
    public function getAllClients() {
        $sql = "SELECT * FROM Client";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Connexion d'un client
    public function login($email, $password) {
        $sql = "SELECT * FROM Client WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client && password_verify($password, $client['password'])) {
            return $client;
        }
        return false;
    }

    // Récupérer le profil d'un client
    public function getProfile($id) {
        $sql = "SELECT * FROM Client WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mise à jour du profil d'un client (pour le client lui-même)
    public function updateProfile($id, $data) {
        $sql = "UPDATE Client SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['telephone'],
            $data['adresse'],
            $id
        ]);
    }

    // Mise à jour d'un client (gestion par le fournisseur)
    public function updateClient($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            // Mise à jour avec le nouveau mot de passe
            $sql = "UPDATE Client SET cin = ?, nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?, password = ?
                    WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['cin'],
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['telephone'],
                $data['adresse'],
                password_hash($data['password'], PASSWORD_BCRYPT),
                $id
            ]);
        } else {
            // Mise à jour sans changer le mot de passe
            $sql = "UPDATE Client SET cin = ?, nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?
                    WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['cin'],
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['telephone'],
                $data['adresse'],
                $id
            ]);
        }
    }

    // Suppression d'un client (gestion par le fournisseur)
    public function deleteClient($id) {
        $sql = "DELETE FROM Client WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
