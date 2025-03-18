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

    public static function voter(int $idDebate, int $idArgument): void
    {
        $argumentModel = new ArgumentModel();
        $argument = $argumentModel->getById($idArgument);
        if ($argumentModel->voter($argument)) {
            header("location: /debate/$idDebate/arguments");
        } else {
            header("", true, 500);
        }
    }
}