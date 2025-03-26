<?php

namespace Models;

use PDO;
use Entity\Debate;
use Entity\Argument;
class DebatModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function getAllDebatsSortedByParticipants(int $limit, int $offset): array
    {
        $stmt = $this->db->prepare(
            "SELECT d.*,
        (SELECT COUNT(DISTINCT a.id_utilisateur) 
         FROM Argument a
         JOIN Camp c ON a.id_camp = c.id_camp
         WHERE c.id_debat = d.id_debat)
        +
        (SELECT COUNT(DISTINCT v.id_utilisateur) 
         FROM Voter v
         JOIN Argument a ON v.id_arg = a.id_arg
         JOIN Camp c ON a.id_camp = c.id_camp
         WHERE c.id_debat = d.id_debat)
        AS total_participants
    FROM Debat d
    WHERE d.statut = 'Valide'
    ORDER BY total_participants DESC
    LIMIT :limit OFFSET :offset"
        );


        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $debats = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $debats[] = new Debate(
                $row['id_debat'],
                $row['nom_d'],
                $row['desc_d'],
                $row['statut'],
                $row['duree'],
                new \DateTime($row['date_creation']),
                $row['id_utilisateur']
            );
        }

        // Je verifie s'il y a des débats sur la page suivante
        $nextOffset = $offset + $limit;
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) 
        FROM Debat d
        WHERE d.statut = 'Valide'"
        );

        $stmt->execute();
        $totalDebats = $stmt->fetchColumn();

        // Si la page suivante contient des débats, alors il y a encore des débats à afficher
        $noMoreDebates = ($nextOffset >= $totalDebats);

        return [
            'debats' => $debats,
            'noMoreDebates' => $noMoreDebates
        ];
    }


    public function calculateStatsForDebat(int $idDebat): array
    {
        // Récupérer les ID des camps liés au débat
        $queryCamps = "SELECT id_camp FROM Camp WHERE id_debat = :id_debat";
        $stmt = $this->db->prepare($queryCamps);
        $stmt->execute(['id_debat' => $idDebat]);
        $camps = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (!$camps) {
            return [
                'nb_vote_camp_1' => 0,
                'nb_vote_camp_2' => 0,
                'pourcentage_camp_1' => 50,
                'pourcentage_camp_2' => 50,
                'nb_participants' => 0
            ];
        }

        // Requête pour récupérer les votes par camp
        $queryVotes = "
        SELECT 
            c.id_camp,
            COUNT(DISTINCT v.id_utilisateur) AS nb_votes
        FROM Camp c
        LEFT JOIN Argument a ON c.id_camp = a.id_camp
        LEFT JOIN Voter v ON a.id_arg = v.id_arg
        WHERE c.id_debat = :id_debat
        GROUP BY c.id_camp";

        $stmt = $this->db->prepare($queryVotes);
        $stmt->execute(['id_debat' => $idDebat]);
        $votes = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // Calcul du nombre total de votes
        $nbVotesTotal = array_sum($votes) ?: 0;

        // On cherche le nombre total de participants distincts (ayant soit voté, soit posté un argument)
        $queryParticipants = "
        SELECT COUNT(DISTINCT id_utilisateur) AS nb_participants
        FROM (
            SELECT id_utilisateur FROM Argument 
            WHERE id_camp IN (SELECT id_camp FROM Camp WHERE id_debat = :id_debat)
            UNION
            SELECT id_utilisateur FROM Voter 
            WHERE id_arg IN (SELECT id_arg FROM Argument WHERE id_camp IN 
                (SELECT id_camp FROM Camp WHERE id_debat = :id_debat))
        ) AS participants";

        $stmt = $this->db->prepare($queryParticipants);
        $stmt->execute(['id_debat' => $idDebat]);
        $nbParticipants = $stmt->fetchColumn() ?: 0;

        // Associer les votes aux camps corrects
        $nbVotesCamp1 = isset($camps[0]) ? ($votes[$camps[0]] ?? 0) : 0;
        $nbVotesCamp2 = isset($camps[1]) ? ($votes[$camps[1]] ?? 0) : 0;

        // Calcul des pourcentages
        $pourcentageCamp1 = ($nbVotesTotal > 0) ? round(($nbVotesCamp1 / $nbVotesTotal) * 100, 2) : 50;
        $pourcentageCamp2 = ($nbVotesTotal > 0) ? round(($nbVotesCamp2 / $nbVotesTotal) * 100, 2) : 50;

        return [
            'nb_vote_camp_1' => $nbVotesCamp1,
            'nb_vote_camp_2' => $nbVotesCamp2,
            'pourcentage_camp_1' => $pourcentageCamp1,
            'pourcentage_camp_2' => $pourcentageCamp2,
            'nb_participants' => $nbParticipants
        ];
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function getDebatById(int $id): ?Debate
    {
        $stmt = $this->db->prepare("SELECT * FROM Debat WHERE id_debat = :id LIMIT 1");
        $stmt->execute(['id' => $id]);

        if ($stmt->errorCode() !== '00000') {
            return null;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        try {
            $dateCreation = new \DateTime($row['date_creation']);
        } catch (\Exception $e) {
            file_put_contents(__DIR__ . '/debug.log', "Erreur de conversion DateTime : " . $e->getMessage() . "\n", FILE_APPEND);
            return null;
        }

        return new Debate(
            $row['id_debat'],
            $row['nom_d'],
            $row['desc_d'],
            $row['statut'],
            $row['duree'],
            $dateCreation,
            $row['id_utilisateur']
        );
    }

    public function getAllDebatsSortedByDate(int $limit, int $offset): array
    {
        // Préparation de la requête pour récupérer les débats valides, triés par date de création (du plus récent au plus ancien)
        $stmt = $this->db->prepare(
            "SELECT d.*, 
                COALESCE(MAX(a.date_poste), d.date_creation) AS dernier_argument
         FROM Debat d
         LEFT JOIN Camp c ON d.id_debat = c.id_debat
         LEFT JOIN Argument a ON c.id_camp = a.id_camp
         WHERE d.statut = 'Valide'
         GROUP BY d.id_debat
         ORDER BY dernier_argument DESC
         LIMIT :limit OFFSET :offset"
        );

        // Liaison des paramètres
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $debats = [];
        // Récupérer tous les résultats
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $debats[] = new Debate(
                $row['id_debat'],
                $row['nom_d'],
                $row['desc_d'],
                $row['statut'],
                $row['duree'],
                new \DateTime($row['date_creation']),
                $row['id_utilisateur']
            );
        }

        // Si on a obtenu moins de résultats que la limite
        if (count($debats) < $limit) {
            // S'il y a moins de résultats que prévu, c'est qu'il n'y a plus de débats
            $noMoreDebates = true;
        } else {
            // Ya encore des débats
            $noMoreDebates = false;
        }

        return [
            'debats' => $debats,
            'noMoreDebates' => $noMoreDebates
        ];
    }


}