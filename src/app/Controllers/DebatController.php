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
    public function listDebats(): void
    {
        $perPage = 5;
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($page - 1) * $perPage;

        // Récupérer les débats tendance (par nombre de participants)
        $responseTendance = $this->debatModel->getAllDebatsSortedByParticipants($perPage, $offset);
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

        $statsRecents = [];
        foreach ($debatsRecents as $debat) {
            $statsRecents[$debat->getId()] = $this->debatModel->calculateStatsForDebat($debat->getId());
        }

        // Récupération du classement des utilisateurs
        $userRanking = $this->userStatModel->getUserRankingByVotes(5);

        extract(compact('debatsRecents', 'debatsTendance', 'statsRecents', 'statsTendance', 'perPage', 'page', 'noMoreDebatsRecents', 'noMoreDebatsTendance', 'userRanking'));
        require_once __DIR__ . '/../Views/Debat/debats_list.php';
    }


    /**
     * @throws \DateMalformedStringException
     */
    public function viewDebat(int $id): void
    {
        $debat = $this->debatModel->getDebatById($id);

        if (!$debat) {
            die("Débat introuvable !");
        }

        $arguments = $this->debatModel->getArgumentsByDebat($id);

        extract(compact('debat', 'arguments'));
        require_once __DIR__ . '/../Views/Debat/debat_detail.php';
    }
}