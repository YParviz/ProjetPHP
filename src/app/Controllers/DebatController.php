<?php
namespace Controllers;

use Models\DebatModel;
use Models\UserStatModel;
use PDO;

class DebatController
{
    private DebatModel $debatModel;
    private UserStatModel $userStatModel;

    public function __construct(DebatModel $debatModel, UserStatModel $userStatModel)
    {
        $this->debatModel = $debatModel;
        $this->userStatModel = $userStatModel;
    }


     // Liste des débats
    // Contrôleur : listDebats
    public function listDebats($page = 1): void
    {
        $perPage = 3; // Nombre de débats par page
        $page = max(1, intval($page)); // Si $page est moins que 1, on le force à 1
        $offset = ($page - 1) * $perPage;

        // Récupérer les débats tendance (par nombre de participants) - Limité à 3 par page
        $responseTendance = $this->debatModel->getAllDebatsSortedByParticipants($perPage, $offset);
        $debatsTendance = $responseTendance['debats'];

        // Récupérer les débats récents (par date) - Limité à 3 par page
        $responseRecents = $this->debatModel->getAllDebatsSortedByDate($perPage, $offset);
        $debatsRecents = $responseRecents['debats'];

        // Calcul des statistiques pour les débats tendance
        $statsTendance = $this->getDebatStats($debatsTendance);

        // Calcul des statistiques pour les débats récents
        $statsRecents = $this->getDebatStats($debatsRecents);

        // Vérifiez s'il y a des débats sur la page suivante
        $nextPageDebatsTendance = $this->debatModel->getAllDebatsSortedByParticipants($perPage, $offset + $perPage);
        $nextPageDebatsRecents = $this->debatModel->getAllDebatsSortedByDate($perPage, $offset + $perPage);
        $noMoreDebatsNextPage = empty($nextPageDebatsTendance['debats']) && empty($nextPageDebatsRecents['debats']);

        // Récupération du classement des utilisateurs
        $userRanking = $this->userStatModel->getUserRankingByVotes(5);

        // Envoi des variables à la vue
        extract(compact('debatsRecents', 'debatsTendance', 'statsRecents', 'statsTendance', 'perPage', 'page', 'noMoreDebatsNextPage', 'userRanking'));
        require_once __DIR__ . '/../Views/Debat/debats_list.php';
    }


    private function getDebatStats(array $debats): array
    {
        $stats = [];
        foreach ($debats as $debat) {
            $stats[$debat->getId()] = $this->debatModel->calculateStatsForDebat($debat->getId());
        }
        return $stats;
    }

    /**
     * Vue d'un débat spécifique
     */
    public function viewDebat(int $id): void
    {
        try {
            $debat = $this->debatModel->getDebatById($id);
            if (!$debat) {
                header("Location: /debats?error=notfound");
                exit;
            }

            $arguments = $this->debatModel->getArgumentsByDebat($id);

            $viewData = [
                'debat' => $debat,
                'arguments' => $arguments
            ];

            $this->renderView('debat_detail.php', $viewData);

        } catch (\Exception $e) {
            echo "Une erreur est survenue : " . $e->getMessage();
            exit;
        }
    }

    /**
     * Méthode pour rendre la vue avec les données
     */
    private function renderView(string $view, array $data): void
    {
        extract($data);
        require_once __DIR__ . '/../Views/Debat/' . $view;
    }
}
