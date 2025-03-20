<?php

namespace Entity;
use DateTime;

class User {
    private int $id_utilisateur;
    private string $email;
    private string $pseudo;
    private string $mdp;
    private string $role;
    private DateTime $createdAt;

    public function __construct(int $id_utilisateur, string $email, string $pseudo, string $mdp, string $role, DateTime $createdAt) {
    $this->id_utilisateur = $id_utilisateur;
    $this->email = $email;
    $this->pseudo = $pseudo;
    $this->mdp = $mdp;
    $this->role = $role;
    $this->createdAt = $createdAt;
    }

    public function getId_utilisateur(): int { return $this->id_utilisateur; }
    public function getEmail(): string { return $this->email; }
    public function getPseudo(): string { return $this->pseudo; }
    public function getmdp(): string { return $this->mdp; }
    public function getRole(): string { return $this->role; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setPseudo(string $pseudo): void { $this->pseudo = $pseudo; }
    public function setmdp(string $mdp): void { $this->mdp = $mdp; }
    public function setRole(string $role): void { $this->role = $role; }
    public function setCreatedAt(DateTime $createdAt): void { $this->createdAt = $createdAt; }

}