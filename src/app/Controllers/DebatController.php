<?php
namespace Controllers;

use Models\DebatModel;
use Models\UserStatModel;
use PDO;

class DebatController
{
    private DebatModel $debatModel;
    private UserStatModel $userStatModel;
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->debatModel = new DebatModel($db);
        $this->userStatModel = new UserStatModel($db);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function listDebats($page = 1): void
    {
        $perPage = 3; // Gardons 3 par page pour la pagination
        $page = max(1, intval($page));
        $offset = ($page - 1) * $perPage;

        // Récupérer les débats tendance (par nombre de participants)
        $responseTendance = $this->debatModel->getAllDebatsSortedByParticipants(1000, 0); // Récupérer tous les débats tendances
        $debatsTendance = $responseTendance['debats'];
        $noMoreDebatsTendance = $responseTendance['noMoreDebates'];

        // Récupérer les débats récents (par date)
        $responseRecents = $this->debatModel->getAllDebatsSortedByDate($perPage, $offset);
        $debatsRecents = $responseRecents['debats'];
        $noMoreDebatsRecents = $responseRecents['noMoreDebates'];

        // Calcul des statistiques pour les débats
        $statsTendance = [];
        foreach ($debatsTendance as $debat) {
            $statsTendance[$debat->getId()] = $this->debatModel->calculateStatsForDebat($debat->getId());
        }

        // Vérifiez s'il y a des débats sur la page suivante
        $nextPageDebatsTendance = $this->debatModel->getAllDebatsSortedByParticipants($perPage, $offset + $perPage);
        $nextPageDebatsRecents = $this->debatModel->getAllDebatsSortedByDate($perPage, $offset + $perPage);
        $noMoreDebatsNextPage = empty($nextPageDebatsTendance['debats']) && empty($nextPageDebatsRecents['debats']);

        $statsRecents = [];
        foreach ($debatsRecents as $debat) {
            $statsRecents[$debat->getId()] = $this->debatModel->calculateStatsForDebat($debat->getId());
        }

        // Récupération du classement des utilisateurs
        $userRanking = $this->userStatModel->getUserRankingByVotes(5);

        // Envoi des variables à la vue
        extract(compact('debatsRecents', 'debatsTendance', 'statsRecents', 'statsTendance', 'perPage', 'page', 'noMoreDebatsRecents', 'noMoreDebatsTendance', 'userRanking', 'noMoreDebatsNextPage'));
        require_once __DIR__ . '/../Views/Debat/debats_list.php';
    }




    /**
     * @throws \DateMalformedStringException
     */
    public function viewDebat(int $id): void
    {
        file_put_contents(__DIR__ . '/debug.log', "viewDebat appelé avec ID : $id\n", FILE_APPEND);

        // Vous récupérez le débat avec l'ID
        $debat = $this->debatModel->getDebatById($id);

        if (!$debat) {
            file_put_contents(__DIR__ . '/debug.log', "Débat non trouvé pour ID : $id\n", FILE_APPEND);
            header("Location: /debats?error=notfound");
            exit;
        }

        $arguments = $this->debatModel->getArgumentsByDebat($id);

        extract(compact('debat', 'arguments'));
        require_once __DIR__ . '/../Views/Debat/debat_detail.php';
    }



}