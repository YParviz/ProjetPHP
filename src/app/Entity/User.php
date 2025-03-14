<?php

namespace Entity;
class User {
    private int $id;
    private string $email;
    private string $username;
    private string $password;
    private string $role;
    private string $createdAt;

    public function __construct(int $id, string $email, string $username, string $password, string $role, string $createdAt) {
    $this->id = $id;
    $this->email = $email;
    $this->username = $username;
    $this->password = $password;
    $this->role = $role;
    $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function getUsername(): string { return $this->username; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string { return $this->role; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setUsername(string $username): void { $this->username = $username; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setRole(string $role): void { $this->role = $role; }
}