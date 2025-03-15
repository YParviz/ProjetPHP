<?php

namespace Entity;

class Camp {
    private int $id;
    private string $name;
    private int $idDebat;

    public function __construct(int $id, string $name, int $idDebat) {
        $this->id = $id;
        $this->name = $name;
        $this->idDebat = $idDebat;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getIdDebat(): int { return $this->idDebat; }
    public function setIdDebat(int $idDebat): void { $this->idDebat = $idDebat; }
}