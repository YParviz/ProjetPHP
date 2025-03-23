<?php

namespace Controllers;

use Entity\User;
use Models\UserModel;
use Util\View;

class UserController
{
    public static function pageLogin()
    {
        $view = new View();
        $view->render("User/login");
    }

    public static function login(string $email, string $mdp)
{
    $userModel = new UserModel();
    $user = $userModel->getUserByEmail($email);
    $debates = $userModel->getDebatesByEmail($email);

    if ($user && $mdp === $user->getMdp()) {
        session_start();
        $_SESSION['user'] = [
            'id' => $user->getId_utilisateur(),
            'email' => $user->getEmail(),
            'username' => $user->getPseudo(),
            'role' => $user->getRole(),
        ];

        $stats = $userModel->getStatistics($user->getId_utilisateur()) ?: [
            'total_votes' => 0,
            'debates_won' => 0,
        ];

        $view = new View();
        $view->render("User/profilPage", [
            "email" => $user->getEmail(), 
            "pseudo" => $user->getPseudo(), 
            "mdp" => $user->getMdp(),
            "role" => $user->getRole(), 
            "date_creation" => $user->getCreatedAt(),
            "debates" => $debates,
            "stats" => $stats 
        ]);
    } else {
        echo "<script>alert('Email ou mot de passe incorrect !'); window.location.href='/login';</script>";
    }
}

    public static function showProfile()
    {
        session_start();

        if (isset($_SESSION['user']['email'])) {

            $userModel = new UserModel();
            $user = $userModel->getUserByEmail($_SESSION['user']['email']); 
            $debates = $userModel->getDebatesByEmail($_SESSION['user']['email']);

            if ($user) {

                $stats = $userModel->getStatistics($user->getId_utilisateur()) ?: [
                    'total_votes' => 0,
                    'debates_won' => 0,
                ];


                $view = new View();
                $view->render("User/profilPage", [
                    "email" => $user->getEmail(),
                    "pseudo" => $user->getPseudo(),
                    "mdp" => $user->getMdp(),
                    "role" => $user->getRole(),
                    "date_creation" => $user->getCreatedAt(),
                    "debates" => $debates,
                    "stats" => $stats
                ]);
            } else {
                echo "Utilisateur non trouvé.";
            }
        } else {
            echo "Veuillez vous connecter.";
        }
    }


}
