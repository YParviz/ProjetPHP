<?php

namespace Controllers;

use Entity\Argument;
use Models\ArgumentModel;
use Models\CampModel;
use Util\View;

class ArgumentController
{
    public static function print(Argument $argument): void
    {
        $view = new View();
        $view->render("Arguments/print", ["argument" => $argument]);
    }

    public static function list(int $idDebat): void
    {
        if(isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
        } else {
            $userId = 0;
        }
        $argumentModel = new ArgumentModel();
        $arguments = $argumentModel->getByDebat($idDebat);
        $votes = $argumentModel->getArgumentVoted($userId);
        $camp = new CampModel();
        $camps = $camp->getCampsByDebat($idDebat);
        $view = new View();
        $view->render("Arguments/list", ["camp1" => $camps[0], "camp2" => $camps[1], "idDebat" => $idDebat, "arguments" => $arguments, "votes" => $votes]);
    }

    public static function create(int $idDebate): void
    {
        $campModel = new CampModel();
        $camps = $campModel->getCampsByDebat($idDebate);
        $view = new View();
        $view->render("Arguments/create", ["camps" => $camps, "idDebate" => $idDebate]);
    }

    public static function vote(): void
    {
        if (isset($_POST["idArgument"])) {
            $idArgument = $_POST["idArgument"];
            $argumentModel = new ArgumentModel();
            $argument = $argumentModel->getById($idArgument);
            if ($argumentModel->vote($argument)) {
                echo $argument->getVoteNumber();
            } else {
                header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        }
    }

    public static function unvote(): void
    {
        if (isset($_POST["idArgument"])) {
            $idArgument = $_POST["idArgument"];
            $argumentModel = new ArgumentModel();
            $argument = $argumentModel->getById($idArgument);
            if ($argumentModel->unvote($argument)) {
                echo $argument->getVoteNumber();
            } else {
                header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        }
    }

    public static function poste($idDebate): void
    {
        $argumentModel = new ArgumentModel();
        if (isset($_POST["camp"])  and isset($_POST["argument"])) {
            $idCamp = $_POST["camp"];
            $argument = htmlspecialchars($_POST["argument"]);
            if ($argumentModel->createNew($idCamp, $argument, $_SESSION["user"]["id"])) {
                header("Location: /debat/$idDebate");
            } else {
                header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        }
    }
}
