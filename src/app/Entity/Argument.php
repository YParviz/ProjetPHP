<?php

namespace Entity;

class Argument {
    private int $id;
    private string $text;
    private int $idCamp;
    private ?int $idArgPrincipal;
    private int $userId;
    private string $datePosted;
    private int $voteNumber;
    private array $sousArguments;

    public function __construct(int $id, string $text, int $idCamp, ?int $idArgPrincipal, int $userId, string $datePosted, int $voteNumber, array $sousArguments) {
        $this->id = $id;
        $this->text = $text;
        $this->idCamp = $idCamp;
        $this->idArgPrincipal = $idArgPrincipal;
        $this->userId = $userId;
        $this->datePosted = $datePosted;
        $this->voteNumber = $voteNumber;
        $this->sousArguments = $sousArguments;
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

    public function getVoteNumber(): int { return $this->voteNumber; }
    public function setVoteNumber(int $voteNumber): void { $this->voteNumber = $voteNumber; }

    public function getNumCamp(): int { return $this->idCamp % 2 === 1 ? 1 : 2; }

    public function getSousArguments(): array { return $this->sousArguments; }

    public function setSousArguments(array $sousArguments): void { $this->sousArguments = $sousArguments; }

    public function __toString(): string
    {
        return $this->id . " : " . $this->text . " : " . $this->voteNumber . "<br>";
    }
}