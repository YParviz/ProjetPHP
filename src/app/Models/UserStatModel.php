<?php
namespace Models;

use PDO;

class UserStatModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getUserRankingByVotes($limit = 5)
    {
        // On calcule le mois actuel
        $currentMonth = date('Y-m');
        $firstDayOfMonth = $currentMonth . '-01';
        $lastDayOfMonth = date('Y-m-t');

        $query = "SELECT 
                    u.id_utilisateur, 
                    u.pseudo, 
                    COUNT(v.id_arg) as total_votes
                  FROM 
                    Utilisateur u
                  JOIN 
                    Argument a ON u.id_utilisateur = a.id_utilisateur
                  JOIN 
                    Voter v ON a.id_arg = v.id_arg
                  WHERE 
                    v.date_vote BETWEEN :firstDay AND :lastDay
                  GROUP BY 
                    u.id_utilisateur
                  ORDER BY 
                    total_votes DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':firstDay', $firstDayOfMonth, PDO::PARAM_STR);
        $stmt->bindParam(':lastDay', $lastDayOfMonth, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}