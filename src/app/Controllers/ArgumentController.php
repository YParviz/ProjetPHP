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
        $argumentModel = new ArgumentModel();
        $arguments = $argumentModel->getByDebat($idDebat);
        $camp = new CampModel();
        $camps = $camp->getCampsByDebat($idDebat);
        $view = new View();
        $view->render("Arguments/list", ["camp1" => $camps[0], "camp2" => $camps[1], "idDebat" => $idDebat, "arguments" => $arguments]);
    }

    public static function voter(): void
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
}