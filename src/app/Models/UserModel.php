<?php

namespace Models;

use Entity\User;
use Entity\Debate;
use PDO;
use Util\Database;
use DateTime;

class UserModel
{
    private $pdo;

    public function __construct(){
        $this->pdo = Database::connect();
    }

    public function login(string $email, string $mdp): bool {
        $statement = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE email = :email AND mdp = :mdp");
        $statement->execute(['email' => $email, 'mdp' => $mdp]);
        return $statement->rowCount() === 1;
    }

    public function getUserByEmail(string $email): User {
        $statement = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE email = :email");
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $this->createByTab($user);
    }

    public function getDebatesByEmail(string $email): array {
        $statement = $this->pdo->prepare("
            SELECT D.id_debat, D.nom_d, D.desc_d, D.statut, D.duree, 
                   D.date_creation, D.id_utilisateur,
                   (SELECT COUNT(DISTINCT Argument.id_utilisateur)
                    FROM Debat NATURAL JOIN Camp
                    JOIN Argument ON Argument.id_camp = Camp.id_camp
                    LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
                    WHERE Debat.id_debat = D.id_debat
                    AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide)
                   ) AS nb_participants
            FROM Debat D 
            JOIN Utilisateur U ON D.id_utilisateur = U.id_utilisateur 
            WHERE U.email = :email 
            ORDER BY D.date_creation DESC 
            LIMIT 3
        ");

        $statement->execute(['email' => $email]);
        $debates = $statement->fetchAll(PDO::FETCH_ASSOC);

        $debateObjects = [];
        foreach ($debates as $debate) {
            $debateObjects[] = $this->createDebateByTab($debate);
        }

        return $debateObjects;
    }

    public function getStatistics(int $userId): array {
        $stats = [];
    
        // Nombre total de votes cumulés
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS total_votes 
            FROM Voter 
            WHERE id_utilisateur = :userId
        ");
        $stmt->execute(['userId' => $userId]);
        $stats['total_votes'] = $stmt->fetchColumn() ?: 0;
    
        // Nombre de débats remportés
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS debates_won
            FROM Debat
            WHERE id_utilisateur = :userId
            AND statut = 'Valide'
        ");
        $stmt->execute(['userId' => $userId]);
        $stats['debates_won'] = $stmt->fetchColumn() ?: 0;

        return $stats;
    }
    
    

    public function createByTab(array $data): User
    {
        return new User(
            $data['id_utilisateur'],
            $data['email'],
            $data['pseudo'],
            $data['mdp'],
            $data['role'],
            new DateTime($data['date_creation'])
        );
    }

    public static function createDebateByTab(array $data): Debate
    {
        return new Debate(
            $data['id_debat'],               
            $data['nom_d'],             
            $data['desc_d'],      
            $data['statut'],           
            $data['duree'],         
            new DateTime($data['date_creation']),  
            $data['id_utilisateur'],
            $data['nb_participants'] ?? 0       
        );
    }


}