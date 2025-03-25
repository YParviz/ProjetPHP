<?php

namespace Models;

use Entity\Camp;
use Util\Database;

class CampModel
{
    private $pdo;
    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    public function getCampsByDebat(int $idDebat) : array
    {
        $statement = $this->pdo->prepare("SELECT * FROM Camp WHERE id_debat = :id_debat");
        $statement->execute(['id_debat' => $idDebat]);
        $camps = [];
        foreach ($statement->fetchAll() as $camp) {
            $camps[] = $this->createByTab($camp);
        }
        return $camps;
    }

    private function createByTab(array $camp): Camp
    {
        return new Camp(
            $camp['id_camp'],
            $camp['nom_camp'],
            $camp['id_debat'],
        );
    }

}
