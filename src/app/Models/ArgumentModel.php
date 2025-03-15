<?php

namespace Models;

use Entity\Argument;
use Util\Database;

class ArgumentModel
{
    private $pdo;

    public function __construct(){
        $this->pdo = Database::connect();
    }

    public function getById(int $id): Argument
    {
        $statement = $this->pdo->prepare("SELECT * FROM argument WHERE id_arg = :id");
        $statement->execute([
            "id" => $id
        ]);
        $argument = $statement->fetch(\PDO::FETCH_ASSOC);
        $argumentEntity = new Argument($argument["id_arg"], $argument["texte"], $argument["id_camp"], $argument["id_arg_principal"], $argument["id_utilisateur"], $argument["date_poste"]);
        return $argumentEntity;
    }

    public function delete(Argument $argument): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM argument WHERE id_arg = :id");
        $statement->execute(["id" => $argument->getId()]);
        return $statement->rowCount() === 1;
    }

}