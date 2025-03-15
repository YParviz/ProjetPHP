<?php

namespace Controllers;

use Entity\Argument;
use Util\View;

class ArgumentController
{
    public function print(Argument $argument): void
    {
        $view = new View();
        $view->render("Arguments/print", ["argument" => $argument]);
    }
}