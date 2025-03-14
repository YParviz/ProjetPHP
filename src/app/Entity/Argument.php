<?php

namespace Entity;

use DateTime;

class Argument {
    private int $id;
    private string $texte;
    private int $idCamp;
    private int $idArgPrincipal;
    private int $idUtilisateur;
    private DateTime $datePoste;

    public function __construct(int $id, string $texte, int $idCamp, ?int $idArgPrincipal, int $idUtilisateur, DateTime $datePoste) {
        $this->id = $id;
        $this->texte = $texte;
        $this->idCamp = $idCamp;
        $this->idArgPrincipal = $idArgPrincipal;
        $this->idUtilisateur = $idUtilisateur;
        $this->datePoste = $datePoste;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTexte(): string { return $this->texte; }
    public function setTexte(string $texte): void { $this->texte = $texte; }

    public function getIdCamp(): int { return $this->idCamp; }
    public function setIdCamp(int $idCamp): void { $this->idCamp = $idCamp; }

    public function getIdArgPrincipal(): ?int { return $this->idArgPrincipal; }
    public function setIdArgPrincipal(?int $idArgPrincipal): void { $this->idArgPrincipal = $idArgPrincipal; }

    public function getIdUtilisateur(): int { return $this->idUtilisateur; }
    public function setIdUtilisateur(int $idUtilisateur): void { $this->idUtilisateur = $idUtilisateur; }

    public function getDatePoste(): DateTime { return $this->datePoste; }
    public function setDatePoste(DateTime $datePoste): void { $this->datePoste = $datePoste; }
}