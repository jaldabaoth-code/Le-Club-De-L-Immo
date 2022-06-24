<?php

namespace App\Model;

class PropertyTypeManager extends AbstractManager
{
    public const TABLE = 'propertyType';

    public function selectPropertyTypeByPropertyId(int $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE propertyType.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
