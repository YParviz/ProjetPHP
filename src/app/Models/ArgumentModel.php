<?php

namespace Models;

use Entity\Argument;
use PDO;
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
        $argument = $statement->fetch(PDO::FETCH_ASSOC);
        return $this->createByTab($argument);
    }

    public function create(Argument $argument): bool
    {
        $statement = $this->pdo->prepare("INSERT INTO argument (date_poste, texte, id_camp, id_arg_principal, id_utilisateur) VALUES (:date_poste, :texte, :id_camp, :id_arg_principal, :id_utilisateur)");
        $statement->execute([
            "date_poste" => $argument->getDatePoste(),
            "texte" => $argument->getTexte(),
            "id_camp" => $argument->getIdCamp(),
            "id_arg_principal" => $argument->getIdArgPrincipal() !== null ? $argument->getIdArgPrincipal() : "NULL",
            "id_utilisateur" => $argument->getIdUtilisateur()
        ]);
        return $statement->rowCount() === 1;
    }
    public function delete(Argument $argument): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM argument WHERE id_arg = :id");
        $statement->execute(["id" => $argument->getId()]);
        return $statement->rowCount() === 1;
    }

    private function createByTab($argument): Argument
    {
        return new Argument(
            $argument["id_arg"],
            $argument["texte"],
            $argument["id_camp"],
            $argument["id_arg_principal"],
            $argument["id_utilisateur"],
            $argument["date_poste"]
        );
    }

}