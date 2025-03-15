<?php

namespace Entity;

class Argument {
    private int $id;
    private string $text;
    private int $idCamp;
    private ?int $idArgPrincipal;
    private int $userId;
    private string $datePosted;

    public function __construct(int $id, string $text, int $idCamp, ?int $idArgPrincipal, int $userId, string $datePosted) {
        $this->id = $id;
        $this->text = $text;
        $this->idCamp = $idCamp;
        $this->idArgPrincipal = $idArgPrincipal;
        $this->userId = $userId;
        $this->datePosted = $datePosted;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getText(): string { return $this->text; }
    public function setText(string $text): void { $this->text = $text; }

    public function getIdCamp(): int { return $this->idCamp; }
    public function setIdCamp(int $idCamp): void { $this->idCamp = $idCamp; }

    public function getIdArgPrincipal(): ?int { return $this->idArgPrincipal; }
    public function setIdArgPrincipal(?int $idArgPrincipal): void { $this->idArgPrincipal = $idArgPrincipal; }

    public function getUserId(): int { return $this->userId; }
    public function setUserId(int $userID): void { $this->userId = $userID; }

    public function getDatePosted(): string { return $this->datePosted; }
    public function setDatePosted(string $datePosted): void { $this->datePosted = $datePosted; }
}