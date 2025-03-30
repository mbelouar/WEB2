<?php
require_once 'Client.php';

class Particulier extends Client {
    private $cin;
    private $dateNaissance;

    public function __construct($id, $nom, $adresse, $cin, $dateNaissance) {
        parent::__construct($id, $nom, $adresse);
        $this->cin = $cin;
        $this->dateNaissance = $dateNaissance;
    }

    public function getCin() {
        return $this->cin;
    }

    public function setCin($cin) {
        $this->cin = $cin;
    }

    public function getDateNaissance() {
        return $this->dateNaissance;
    }

    public function setDateNaissance($dateNaissance) {
        $this->dateNaissance = $dateNaissance;
    }

    public function payerFacture() {
        return "Le particulier ne peut payer que par carte bancaire ou espèces.";
    }

    public function reclamation() {
        return "Réclamation d'un particulier (CIN: {$this->cin})";
    }

    public function __toString() {
        return parent::__toString() . ", Particulier [CIN: {$this->cin}, Date de naissance: {$this->dateNaissance}]";
    }
}
?> 