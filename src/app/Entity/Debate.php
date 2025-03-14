<?php

namespace Entity;
class Debate {
    private int $id;
    private string $name;
    private string $description;
    private string $status;
    private int $duration;
    private string $createdAt;
    private int $userId;

    public function __construct(int $id, string $name, string $description, string $status, int $duration, string $createdAt, int $userId) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->status = $status;
    $this->duration = $duration;
    $this->createdAt = $createdAt;
    $this->userId = $userId;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getStatus(): string { return $this->status; }
    public function getDuration(): int { return $this->duration; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getUserId(): int { return $this->userId; }

    public function setName(string $name): void { $this->name = $name; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function setDuration(int $duration): void { $this->duration = $duration; }
}