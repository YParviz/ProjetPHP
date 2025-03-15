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
        $statement = $this->pdo->prepare("SELECT * FROM Argument WHERE id_arg = :id");
        $statement->execute([
            "id" => $id
        ]);
        $argument = $statement->fetch(PDO::FETCH_ASSOC);
        return $this->createByTab($argument);
    }

    public function getByDebat(int $idDebat): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM Argument NATURAL JOIN Camp WHERE id_debat = :id_debat AND id_arg_principal IS NULL");
        $statement->execute(["id_debat" => $idDebat]);
        $arguments = $statement->fetchAll(PDO::FETCH_ASSOC);

        $argumentsList = [[],[]];
        foreach ($arguments as $argument) {
            if ($argument["id_camp"] % 2 == 1){
                $argumentsList[0][] = $this->createByTab($argument);
            } else {
                $argumentsList[1][] = $this->createByTab($argument);
            }
        }
        return $argumentsList;
    }
    public function create(Argument $argument): bool
    {
        $statement = $this->pdo->prepare("INSERT INTO Argument (date_poste, texte, id_camp, id_arg_principal, id_utilisateur) VALUES (:date_poste, :texte, :id_camp, :id_arg_principal, :id_utilisateur)");
        $statement->execute([
            "date_poste" => $argument->getDatePosted(),
            "texte" => $argument->getText(),
            "id_camp" => $argument->getIdCamp(),
            "id_arg_principal" => $argument->getIdArgPrincipal() !== null ? $argument->getIdArgPrincipal() : "NULL",
            "id_utilisateur" => $argument->getUserId()
        ]);
        return $statement->rowCount() === 1;
    }
    public function delete(Argument $argument): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM Argument WHERE id_arg = :id");
        $statement->execute(["id" => $argument->getId()]);
        return $statement->rowCount() === 1;
    }

    private function getVotesNumber(int $argumentId): int {
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM Voter WHERE id_arg = :id");
        $statement->execute(["id" => $argumentId]);
        return $statement->fetchColumn();
    }

    private function createByTab($argument): Argument
    {
        return new Argument(
            $argument["id_arg"],
            $argument["texte"],
            $argument["id_camp"],
            $argument["id_arg_principal"],
            $argument["id_utilisateur"],
            $argument["date_poste"],
            $this->getVotesNumber($argument["id_arg"])
        );
    }

}