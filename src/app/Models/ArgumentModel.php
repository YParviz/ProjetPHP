<?php

namespace Models;

use Entity\Argument;
use Exception;
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
        $statement = $this->pdo->prepare(
            "SELECT Argument.id_arg, Argument.date_poste, Argument.texte, id_camp, Argument.id_arg_principal, Argument.id_utilisateur
                    FROM Argument NATURAL JOIN Camp
                    JOIN Debat ON Camp.id_debat = Debat.id_debat
                    WHERE Debat.id_debat = :id_debat
                      AND id_arg_principal IS NULL
                    ORDER BY Argument.date_poste"
        );
        $statement->execute(["id_debat" => $idDebat]);
        $arguments = $statement->fetchAll(PDO::FETCH_ASSOC);

        $argumentsList = [[],[]];
        foreach ($arguments as $argument) {
            $argument["sous_arguments"] = $this->getAllSousArguments($argument["id_arg"]);
            if ($argument["id_camp"] % 2 == 1){
                $argumentsList[0][] = $this->createByTab($argument);
            } else {
                $argumentsList[1][] = $this->createByTab($argument);
            }
        }
        return $argumentsList;
    }

    public function getAllSousArguments(int $idArgument): array
    {
        $statement = $this->pdo->prepare(
            "SELECT Argument.id_arg, Argument.date_poste, Argument.texte, id_camp, Argument.id_arg_principal, Argument.id_utilisateur
                    FROM Argument NATURAL JOIN Camp
                    WHERE id_arg_principal = :id_arg
                    ORDER BY Argument.date_poste"
        );
        $statement->execute(["id_arg" => $idArgument]);
        $arguments = $statement->fetchAll(PDO::FETCH_ASSOC);

        $argumentsList = [];
        foreach ($arguments as $argument) {
            $argumentsList[] = $this->createByTab($argument);
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

    public function vote(Argument $argument): bool
    {
        try {
            $statement = $this->pdo->prepare("INSERT INTO Voter (id_utilisateur, id_arg) VALUE (:id_utilisateur, :id_arg)");
            $statement->execute([
                "id_utilisateur" => 1,
                "id_arg" => $argument->getId()
            ]);
        }
        catch (Exception $e) {
            return false;
        }
        $argument->setVoteNumber($argument->getVoteNumber()+1);
        return $statement->rowCount() === 1;
    }

    public function unvote(Argument $argument): bool
    {
        try {
            $statement = $this->pdo->prepare("DELETE FROM Voter WHERE id_arg = :id_arg AND id_utilisateur = :id_utilisateur");
            $statement->execute([
                "id_utilisateur" => 1,
                "id_arg" => $argument->getId()
            ]);
        }
        catch (Exception $e) {
            return false;
        }
        $argument->setVoteNumber($argument->getVoteNumber()-1);
        return $statement->rowCount() === 1;
    }

    public function getArgumentVoted(int $userId): array
    {
        $statement = $this->pdo->prepare("SELECT id_arg FROM Voter WHERE id_utilisateur = :id");
        $statement->execute(["id" => $userId]);
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
    private function getVotesNumber(int $argumentId): int
    {
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM Voter WHERE id_arg = :id");
        $statement->execute(["id" => $argumentId]);
        return $statement->fetchColumn();
    }

    private function createByTab($argument): Argument
    {
        $sousArguments = isset($argument["sous_arguments"]) ? $argument["sous_arguments"] : [];
        return new Argument(
            $argument["id_arg"],
            $argument["texte"],
            $argument["id_camp"],
            $argument["id_arg_principal"],
            $argument["id_utilisateur"],
            $argument["date_poste"],
            $this->getVotesNumber($argument["id_arg"]),
            $sousArguments
        );
    }

}