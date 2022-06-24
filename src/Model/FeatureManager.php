<?php

namespace App\Model;

class FeatureManager extends AbstractManager
{
    public const TABLE = 'feature';

    public function selectOneByName(string $name)
    {
        $statement = $this->pdo->prepare("SELECT id FROM " . self::TABLE . " WHERE flaticonName=:name");
        $statement->bindValue('name', 'flaticon-' . $name, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }
}
