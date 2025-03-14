<?php

namespace Entity;
use DateTime;

class Debate {
    private int $id;
    private string $name;
    private string $description;
    private string $status;
    private int $duration;
    private DateTime $creationDate;
    private int $userId;

    // Constructor
    public function __construct(
        int $id,
        string $name,
        string $description,
        string $status,
        int $duration,
        DateTime $creationDate,
        int $userId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->duration = $duration;
        $this->creationDate = $creationDate;
        $this->userId = $userId;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function getCreationDate(): DateTime {
        return $this->creationDate;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    // Setters
    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function setDuration(int $duration): void {
        $this->duration = $duration;
    }

    public function setCreationDate(DateTime $creationDate): void {
        $this->creationDate = $creationDate;
    }

    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }
}