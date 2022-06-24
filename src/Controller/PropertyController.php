<?php

namespace App\Controller;

use App\Model\PropertyManager;
use App\Model\PhotoManager;
use App\Model\PropertyTypeManager;
use App\Model\PropertyFeatureManager;
use App\Model\SectorManager;

class PropertyController extends AbstractController
{
    public const TRANSACTIONS = [
        "A Vendre",
        "A Louer",
        ];

    public function index()
    {
        $propertyManager = new PropertyManager();
        $propertyTypeManager = new PropertyTypeManager();
        $propertyTypes = $propertyTypeManager->selectAll('name', 'ASC');
        $sectorManager = new SectorManager();
        $sectors = $sectorManager->selectAll('name', 'ASC');
        // Validate the input data by calling the method correspondant
        $errors = $searchType = [];
        $searchType = array_map('trim', $_GET);
        $propertyTypeId = $sectorId =  $budget = null;
        $transaction = null;
        if (!empty($searchType)) {
            $errors = $this->validateSearchTypes($searchType, $propertyTypes, $sectors);
        }

        if (!empty($searchType) && empty($errors)) {
            // Convert the variables into integer data type
            $propertyTypeId = intval($searchType['propertyType']);
            $sectorId = intval($searchType['sector']);
            $budget = intval($searchType['budget']);
            $transaction = $searchType['transaction'];
        }
        $properties = $propertyManager->selectProperties($transaction, $propertyTypeId, $sectorId, $budget);
        return $this->twig->render('Property/index.html.twig', [
            'properties' => $properties,
            'propertyTypes' => $propertyTypes,
            'sectors' => $sectors,
            'searchType' => $searchType,
            'transactions' => self::TRANSACTIONS,
            'errors' => $errors,
        ]);
    }

    // Create a method to validate the input and selected fields
    private function validateSearchTypes($searchType, $propertyTypes, $sectors): array
    {
        $errors = [];
        $errors = $this->validateTransaction($searchType, $errors);
        $errors = $this->validatePropertyType($searchType, $propertyTypes, $errors);
        $errors = $this->validateSector($searchType, $sectors, $errors);
        $errors = $this->validateBudget($searchType, $errors);
        return $errors;
    }

    private function validateTransaction($searchType, $errors): array
    {
        if (!empty($searchType['transaction']) && !in_array($searchType['transaction'], self::TRANSACTIONS)) {
            $errors[] = 'Veuillez choisir un type de transaction valide!';
        }
        return $errors;
    }

    private function validatePropertyType($searchType, $propertyTypes, $errors): array
    {
        if (!empty($searchType['propertyType']) && !$this->searchId($searchType['propertyType'], $propertyTypes)) {
            $errors[] = 'Veuillez choisir un type de bien valide!';
        }
        return $errors;
    }

    private function validateSector($searchType, $sectors, $errors): array
    {
        if (!empty($searchType['sector']) && !$this->searchId($searchType['sector'], $sectors)) {
            $errors[] = 'Veuillez choisir un type de bien valide!';
        }
        return $errors;
    }

    private function validateBudget($searchType, $errors): array
    {
        if (!empty($searchType['budget']) && !is_numeric($searchType['budget'])) {
            $errors[] = 'Veuillez entrer un nombre';
        } elseif (intval($searchType['budget']) < 0) {
            $errors[] = 'Veuillez entrer un nombre positif';
        }
        return $errors;
    }

    // Created a method to search the id of each input data in the corresponding array
    private function searchId(string $searchValue, array $searchTypeArray): bool
    {
        foreach ($searchTypeArray as $searchType) {
            if ($searchValue == $searchType['id']) {
                return true;
            }
        }
        return false;
    }

    public function show(int $idProperty)
    {
        if (!empty($idProperty)) {
            $pFeaturesManager = new PropertyFeatureManager();
            $featuresByPropertyId = $pFeaturesManager->selectFeaturesByPropertyId($idProperty);
            $propertyFeatures = [];
            /* associative array */
            foreach ($featuresByPropertyId as $featureByPropertyId) {
                $propertyFeatures[$featureByPropertyId['flaticonName']] = $featureByPropertyId;
            }
            $propertyManager = new PropertyManager();
            $property = $propertyManager->selectOneById($idProperty);
            $sectorManager = new SectorManager();
            $sector = $sectorManager->selectOneById($property['sector_id']);
            $propertyTypeManager = new PropertyTypeManager();
            $propertyType = $propertyTypeManager->selectPropertyTypeByPropertyId($property['property_type_id']);
            $property['name'] = $propertyType['name'];
            $property['features'] = $propertyFeatures;
        } else {
            $property = null;
            $property = null;
            $propertyFeatures = null;
            $sector = null;
        }
        $photoManager = new PhotoManager();
        $photos = $photoManager->selectByPropertyId($idProperty);
        return $this->twig->render('Advertisement/index.html.twig', ['photos' => $photos,
                                                                    'property' => $property,
                                                                    'sector' => $sector]);
    }
}
