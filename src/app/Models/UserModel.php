<?php

namespace Models;

use Entity\User;
use PDO;
use Util\Database;

class CampModel
{
    private $pdo;

    public function __construct(){
        $this->pdo = Database::connect();
    }

    public function login(string $email, string $password):bool{

        $statement = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE email = :email AND mdp = :password");
        $statement->execute(['email' => $email, 'mdp' => $password]);
        return $statement->rowCount() === 1;
    }

    public function getUserByEmail(string $email):User{

        $statement = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE email = :email");
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $this->createByTab($user);
    }

    private function createByTab(array $user): User {
        return new User(
            $user['id_utilisateur'],
            $user['email'],
            $user['pseudo'],
            $user['mdp'],
            $user['role'],
            $user['date_creation']
        );
    }
}