<?php

namespace App\Model;

use App\Model\FeatureManager;

class PropertyFeatureManager extends AbstractManager
{
    public const TABLE = 'property_feature';

    public function insert(array $propertyfeatures, int $propertyId): void
    {
        foreach ($propertyfeatures as $featureName => $featureValue) {
            $featureManager = new FeatureManager();
            $featureId = $featureManager->selectOneByName($featureName);
            if ($featureId) {
                $query = "INSERT INTO " . self::TABLE . " (`number`, `property_id`, `feature_id`)
                VALUES (:number, :propertyId, :featureId)";
                $statement = $this->pdo->prepare($query);
                $statement->bindValue('number', $featureValue, \PDO::PARAM_INT);
                $statement->bindValue('propertyId', $propertyId, \PDO::PARAM_INT);
                $statement->bindValue('featureId', $featureId['id'], \PDO::PARAM_INT);
                $statement->execute();
            }
        }
    }

    public function selectFeaturesByPropertyId(int $id): array
    {
        $statement = $this->pdo->prepare("SELECT feature.*, number FROM " . self::TABLE . " INNER JOIN feature ON "
        . self::TABLE . ".feature_id=feature.id WHERE property_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
