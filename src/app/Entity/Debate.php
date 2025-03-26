<?php

namespace Entity;

use DateTime;

class Debate {
    private int $id_debat;        // Correspond à 'id_debat'
    private string $nom_d;        // Correspond à 'nom_d'
    private string $desc_d;       // Correspond à 'desc_d'
    private string $statut;       // Correspond à 'statut'
    private int $duree;           // Correspond à 'duree'
    private DateTime $date_creation; // Correspond à 'date_creation'
    private int $id_utilisateur;  // Correspond à 'id_utilisateur'
    private int $nb_participants; // Ajout du nombre de participants

    // Constructeur
    public function __construct(
        int $id_debat,
        string $nom_d,
        string $desc_d,
        string $statut,
        int $duree,
        DateTime $date_creation,
        int $id_utilisateur,
        int $nb_participants = 0 
    ) {
        $this->id_debat = $id_debat;
        $this->nom_d = $nom_d;
        $this->desc_d = $desc_d;
        $this->statut = $statut;
        $this->duree = $duree;
        $this->date_creation = $date_creation;
        $this->id_utilisateur = $id_utilisateur;
        $this->nb_participants = $nb_participants; 
    }

    // Getters
    public function getIdDebat(): int {
        return $this->id_debat;
    }

    public function getNomD(): string {
        return $this->nom_d;
    }

    public function getDescD(): string {
        return $this->desc_d;
    }

    public function getStatut(): string {
        return $this->statut;
    }

    public function getDuree(): int {
        return $this->duree;
    }

    public function getDateCreation(): DateTime {
        return $this->date_creation;
    }

    public function getIdUtilisateur(): int {
        return $this->id_utilisateur;
    }

    public function getNbParticipants(): int {
        return $this->nb_participants;
    }

    // Setters
    public function setNomD(string $nom_d): void {
        $this->nom_d = $nom_d;
    }

    public function setDescD(string $desc_d): void {
        $this->desc_d = $desc_d;
    }

    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }

    public function setDuree(int $duree): void {
        $this->duree = $duree;
    }

    public function setDateCreation(DateTime $date_creation): void {
        $this->date_creation = $date_creation;
    }

    public function setIdUtilisateur(int $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    public function setNbParticipants(int $nb_participants): void {
        $this->nb_participants = $nb_participants;
    }
}
