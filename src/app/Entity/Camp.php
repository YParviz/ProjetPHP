<?php

namespace Entity;

class Camp {
    private int $id;
    private string $nomCamp;
    private int $idDebat;

    public function __construct(int $id, string $nomCamp, int $idDebat) {
        $this->id = $id;
        $this->nomCamp = $nomCamp;
        $this->idDebat = $idDebat;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNomCamp(): string { return $this->nomCamp; }
    public function setNomCamp(string $nomCamp): void { $this->nomCamp = $nomCamp; }

    public function getIdDebat(): int { return $this->idDebat; }
    public function setIdDebat(int $idDebat): void { $this->idDebat = $idDebat; }
}