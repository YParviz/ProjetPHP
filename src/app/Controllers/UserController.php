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

            if ($user) {
                $debates = $userModel->getDebatesByEmail($_SESSION['user']['email']);
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
                echo "<script>alert('Utilisateur non trouvé.'); window.location.href='/login';</script>";
            }
        } else {
            echo "<script>alert('Veuillez vous connecter.'); window.location.href='/login';</script>";
        }
    }


    public static function updateProfile()
    {
        session_start();

        if (!isset($_SESSION['user']['id'])) {
            echo "Veuillez vous connecter.";
            return;
        }

        $userId = $_SESSION['user']['id'];
        $pseudo = $_POST['pseudo'] ?? null;
        $email = $_POST['email'] ?? null;
        $mdp = $_POST['mdp'] ?? null;

        if ($pseudo && $email && $mdp) {
            $userModel = new UserModel();
            $userModel->updateUser($userId, $pseudo, $email, $mdp);

            echo "<script>alert('Profil mis à jour avec succès !'); window.location.href='/profile';</script>";
        } else {
            echo "<script>alert('Veuillez remplir tous les champs.'); window.location.href='/profile';</script>";
        }
    }

    public static function deleteProfile()
    {
        session_start();

        if (!isset($_SESSION['user']['id'])) {
            echo "Veuillez vous connecter.";
            return;
        }

        $userId = $_SESSION['user']['id'];
        $userModel = new UserModel();
        $userModel->deleteUser($userId);

        session_destroy();
        echo "<script>alert('Votre compte a été supprimé avec succès.'); window.location.href='/';</script>";
    }


}
