<?php
require_once 'Client.php';

class Entreprise extends Client {
    private $numPatente;
    private $typeSociete;
    private $secteurActivite;

    public function __construct($id, $nom, $adresse, $numPatente, $typeSociete, $secteurActivite) {
        parent::__construct($id, $nom, $adresse);
        $this->numPatente = $numPatente;
        $this->typeSociete = $typeSociete;
        $this->secteurActivite = $secteurActivite;
    }

    public function getNumPatente() {
        return $this->numPatente;
    }

    public function setNumPatente($numPatente) {
        $this->numPatente = $numPatente;
    }

    public function getTypeSociete() {
        return $this->typeSociete;
    }

    public function setTypeSociete($typeSociete) {
        $this->typeSociete = $typeSociete;
    }

    public function getSecteurActivite() {
        return $this->secteurActivite;
    }

    public function setSecteurActivite($secteurActivite) {
        $this->secteurActivite = $secteurActivite;
    }

    public function payerFacture() {
        return "L'entreprise peut payer par chèque, virement ou carte bancaire.";
    }

    public function reclamation() {
        return "Réclamation d'une entreprise du secteur d'activité: {$this->secteurActivite}";
    }

    public function __toString() {
        return parent::__toString() . ", Entreprise [Num Patente: {$this->numPatente}, Type de société: {$this->typeSociete}, Secteur d'activité: {$this->secteurActivite}]";
    }
}
?> 