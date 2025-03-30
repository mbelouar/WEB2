<?php

class Client {
    private $id;
    private $nom;
    private $adresse;
    public static $nbr_user = 0;
    private static $instance = null;

    // Constructeur public pour index_1.php (permettre plusieurs instances)
    private function __construct($id, $nom, $adresse) {
        $this->id = $id;
        $this->nom = $nom;
        $this->adresse = $adresse;
        self::$nbr_user++;
    }

    // Méthode Singleton pour index_2.php
    public static function get_Instance($id, $nom, $adresse) {
        if (self::$instance === null) {
            self::$instance = new Client($id, $nom, $adresse);
        }
        return self::$instance;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    public function afficher() {
        echo "ID: " . $this->id . "<br>";
        echo "Nom: " . $this->nom . "<br>";
        echo "Adresse: " . $this->adresse . "<br>";
    }

    public function __destruct() {
        echo "Client " . $this->nom . " détruit<br>";
    }
}
?> 