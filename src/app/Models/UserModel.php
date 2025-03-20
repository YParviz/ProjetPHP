<?php

namespace Models;

use Entity\User;
use PDO;
use Util\Database;
use DateTime;

class UserModel
{
    private $pdo;

    public function __construct(){
        $this->pdo = Database::connect();
    }

    public function login(string $email, string $mdp):bool{

        $statement = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE email = :email AND mdp = :mdp");
        $statement->execute(['email' => $email, 'mdp' => $mdp]);
        return $statement->rowCount() === 1;
    }

    public function getUserByEmail(string $email):User{

        $statement = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE email = :email");
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $this->createByTab($user);
    }

    public function createByTab(array $data): User
    {
        return new User(
            $data['id_utilisateur'],
            $data['email'],
            $data['pseudo'],
            $data['mdp'],
            $data['role'],
            new DateTime($data['created_at'])
        );
    }
}