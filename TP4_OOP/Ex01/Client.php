<?php

abstract class Client {
    protected $id;
    protected $nom;
    protected $adresse;
    public static $nbr_user = 0;
    private static $instance = null;

    protected function __construct($id, $nom, $adresse) {
        $this->id = $id;
        $this->nom = $nom;
        $this->adresse = $adresse;
        self::$nbr_user++;
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

    // Méthodes abstraites
    abstract public function payerFacture();
    abstract public function reclamation();

    public function __toString() {
        return "Client [ID: {$this->id}, Nom: {$this->nom}, Adresse: {$this->adresse}]";
    }

    public function __destruct() {
        echo "Client " . $this->nom . " détruit<br>";
    }
}
?> 