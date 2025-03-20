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
        $user = $userModel->getUserByEmail($email); // Récupère un objet User

        if ($user && $mdp === $user->getmdp()) { // Correction: Utilisation de getPassword()
            // Démarrer la session et stocker l'utilisateur
            session_start();
            $_SESSION['user'] = [
                'id' => $user->getId_utilisateur(),
                'email' => $user->getEmail(),
                'username' => $user->getPseudo(),
                'role' => $user->getRole(),
            ];
            
            // Affichage du profil
            $view = new View();
            $view->render("User/profilPage", [
                "email" => $user->getEmail(), 
                "pseudo" => $user->getPseudo(), 
                "mdp" => $user->getmdp(),
                "role" => $user->getRole(), 
                "date_creation" => $user->getCreatedAt()
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
                $view = new View();
                $view->render("User/profilPage", [
                    "email" => $user->getEmail(), 
                    "pseudo" => $user->getPseudo(), 
                    "mdp" => $user->getmdp(),
                    "role" => $user->getRole(), 
                    "date_creation" => $user->getCreatedAt()
                ]);
            } else {
                echo "Utilisateur non trouvé.";
            }
        } else {
            echo "Veuillez vous connecter.";
        }
    }
}
