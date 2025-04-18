<?php
namespace Controllers;

use Models\ArgumentModel;
use Models\CampModel;
use Models\DebatModel;
use Models\UserStatModel;
use PDO;
use Util\View;

class DebatController
{
    private DebatModel $debatModel;
    private UserStatModel $userStatModel;
    private $container;

    public function __construct(DebatModel $debatModel, UserStatModel $userStatModel, $container)
    {
        $this->debatModel = $debatModel;
        $this->userStatModel = $userStatModel;
        $this->container = $container;
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

        $view = new View();
        $view->render("Debat/debats_list", [
            "debatsRecents" => $debatsRecents,
            "debatsTendance" => $debatsTendance,
            "statsRecents" => $statsRecents,
            "statsTendance" => $statsTendance,
            "perPage" => $perPage,
            "page" => $page,
            "noMoreDebatsNextPage" => $noMoreDebatsNextPage,
            "userRanking" => $userRanking
        ]);
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

            if(isset($_SESSION['user']['id'])) {
                $userId = $_SESSION['user']['id'];
            } else {
                $userId = 0;
            }
            $argumentModel = new ArgumentModel();
            $arguments = $argumentModel->getByDebat($id);
            $votes = $argumentModel->getArgumentVoted($userId);
            $hasMoreThan5Args = $argumentModel->hasMoreThan5Args($userId, $id);
            $camp = new CampModel();
            $camps = $camp->getCampsByDebat($id);

            $view = new View();
            $view->render("Debat/debat_detail", [
                "debat" => $debat,
                "arguments" => $arguments,
                "camp1" => $camps[0],
                "camp2" => $camps[1],
                "votes" => $votes,
                "hasMoreThan5Args" => $hasMoreThan5Args,
            ]);

        } catch (\Exception $e) {
            echo "Une erreur est survenue : " . $e->getMessage();
            exit;
        }
    }
    public function createDebatForm(): void
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        $view = new View();
        $view->render("Debat/create_debat", []);
    }

    public function createDebat()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? null;
            $description = $_POST['description'] ?? null;
            $camp1 = $_POST['camp1'] ?? null;
            $camp2 = $_POST['camp2'] ?? null;
            $idUtilisateur = $_SESSION['user']['id'] ?? null;

            if ($nom && $description && $camp1 && $camp2 && $idUtilisateur) {
                $debatModel = $this->container->get(DebatModel::class);
                $idDebat = $debatModel->addDebat($nom, $description, $idUtilisateur);

                if ($idDebat) {
                    // Ajout des camps personnalisés saisis par l'utilisateur
                    $debatModel->addCamp($idDebat, $camp1);
                    $debatModel->addCamp($idDebat, $camp2);

                    echo "<script>alert('Débat créé avec succès !'); window.location.href='/debats/1';</script>";
                } else {
                    echo "<script>alert('Erreur lors de la création du débat.'); window.location.href='/createDebat';</script>";
                }
            } else {
                echo "<script>alert('Veuillez remplir tous les champs.'); window.location.href='/createDebat';</script>";
            }
        } else {
            $view = new View();
            $view->render("Debat/create");
        }
    }




}
