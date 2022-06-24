<?php

namespace App\Controller;

use App\Model\PropertyManager;
use App\Model\SectorManager;
use App\Model\PropertyTypeManager;
use App\Model\PhotoManager;
use App\Model\PropertyFeatureManager;
use App\Model\FeatureManager;

class AdminAdvertisementController extends AbstractController
{
    public const MAX_TEXT_LENGTH = 255;
    public const SHORT_TEXT_LENGTH = 25;
    public const TRANSACTION_TYPES = [
        "A vendre",
        "A louer",
        "Autre",
    ];
    public const DIAGNOSTIC_GRADES = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
    public const MAX_UPLOAD_FILE = 1000000;
    public const ALLOWED_MIMES = ['image/jpeg', 'image/png'];

    public function index(): string
    {
        $propertyManager = new PropertyManager();
        $properties = $propertyManager->selectPropertiesForAdmin();

        return $this->twig->render('Admin/Advertisement/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    public function add(): string
    {
        $errors = $advertisement = [];
        $propertyManager = new PropertyManager();
        $sectorManager = new SectorManager();
        $sectors = $sectorManager->selectAll();
        $propertyTypeManager = new PropertyTypeManager();
        $propertyTypes = $propertyTypeManager->selectAll();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $advertisement = array_map('trim', $_POST);
            $advertisement['reference'] = $propertyManager->newPropertyId();
            $errors = $this->validateInput($advertisement, $errors);
            $errors = $this->validateTextSizeInput($advertisement, $errors);
            $errors = $this->validatePositiveInt($advertisement, $errors);
            $errors = $this->validateGradeInput($advertisement, $errors);
            $errors = $this->validateImageFormat($_FILES['photo'], $errors);
            if (empty($errors)) {
                $fileName = uniqid() . '_' . $_FILES['photo']['name'];
                $advertisement['photo'] = $fileName;
                move_uploaded_file(
                    $_FILES['photo']['tmp_name'],
                    __DIR__ . '/../../public/uploads/' . $fileName
                );
                //insert in table property
                $propertyManager = new PropertyManager();
                $propertyId = $propertyManager->insert($advertisement);
                //insert in table photo
                $photoManager = new PhotoManager();
                $photoManager->insert($advertisement, $propertyId);
                //insert in table photo
                $pptyFeatureManager = new PropertyFeatureManager();
                $pptyFeatureManager->insert($advertisement, $propertyId);
                //redirection
                header('Location: /admin');
            }
        }
        return $this->twig->render('Admin/Advertisement/add.html.twig', [
            'errors' => $errors,
            'advertisement' => $advertisement,
            'sectors' => $sectors,
            'propertyTypes' => $propertyTypes,
            'transactionTypes' => self::TRANSACTION_TYPES,
            'diagnosticGrades' => self::DIAGNOSTIC_GRADES,
        ]);
    }

    //Method to ensure every fields had been filled
    public function validateInput(array $advertisement, array $errors): array
    {
        $fieldsList = [
            'transaction' => 'Type de transaction',
            'propertyType' => 'Type de propriété',
            'surface' => 'Surface',
            'price' => 'Prix',
            'address' => 'Adresse',
            'sector' => 'Secteur',
            'rooms' => 'Nombre de pièces',
            'bedrooms' => 'Nombre de chambres',
            'bathrooms' => 'Nombre de salles de bain',
            'toilets' => 'Nombre de toilettes',
            'parking-space' => 'Nombre de places de stationnement',
            'kitchen' => 'Cuisine',
            'lift' => 'Ascenseur',
            'energyPerformance' => 'Performances énergétiques',
            'greenhouseGases' => 'GES',
            'description' => 'Description',
        ];
        $probableNullCriteria = ['surface', 'rooms', 'bedrooms', 'bathrooms',
        'toilets', 'kitchen', 'lift', 'parking-space'];
        foreach ($advertisement as $adKey => $adValue) {
            if (empty($adValue)) {
                //since empty(0) = true, another condition is necessary for properties with no room or bedroom
                if ($adValue != '0' || !in_array($adKey, $probableNullCriteria)) {
                    $errors[] = 'Le champ ' . $fieldsList[$adKey] . ' est requis.';
                }
            }
        }
        return $errors;
    }

    //Method to check strings' length
    public function validateTextSizeInput(array $advertisement, array $errors): array
    {
        if (strlen($advertisement['transaction']) > self::SHORT_TEXT_LENGTH) {
            $errors[] = 'Le champ Transaction doit faire moins de ' . self::SHORT_TEXT_LENGTH . ' caractères.';
        }
        if (strlen($advertisement['address']) > self::MAX_TEXT_LENGTH) {
            $errors[] = 'Le champ Adresse doit faire moins de ' . self::MAX_TEXT_LENGTH . ' caractères.';
        }
        return $errors;
    }

    //Method to ensure positive values had been filled in the proper fields
    public function validatePositiveInt(array $advertisement, array $errors): array
    {
        $integerFieldsList = [
            'surface' => 'Surface',
            'price' => 'Prix',
            'rooms' => 'Nombre de pièces',
            'bedrooms' => 'Nombre de chambres',
            'kitchen' => 'Cuisine',
            'lift' => 'Ascenseur',
        ];
        foreach ($advertisement as $adKey => $adValue) {
            if (array_key_exists($adKey, $integerFieldsList)) {
                if (is_numeric($adValue)) {
                    if ($adValue < 0) {
                            $errors[] = 'La valeur ' . $integerFieldsList[$adKey] . ' doit être positive.';
                    }
                } else {
                    $errors[] = 'La valeur ' . $integerFieldsList[$adKey] . ' doit être un nombre.';
                }
            }
        }
        return $errors;
    }

    //Method to validate the "grades inputs" (energy performance and greenhouse gases)
    public function validateGradeInput(array $advertisement, array $errors): array
    {
        if (!in_array($advertisement['energyPerformance'], self::DIAGNOSTIC_GRADES)) {
            $errors[] = 'Les Performances énergétiques doivent être comprises entre A et G.';
        }
        if (!in_array($advertisement['greenhouseGases'], self::DIAGNOSTIC_GRADES)) {
            $errors[] = 'L\'indice GES doit être compris entre A et G.';
        }
        return $errors;
    }

    //Method to validate files MIME
    public function validateImageFormat(array $file, array $errors): array
    {
        if ($file['error'] != 0) {
            $errors[] = 'Problème dans l\'envoi de fichier.';
        } else {
            if ($file['size'] > self::MAX_UPLOAD_FILE) {
                $errors[] = 'Le fichier doit faire moins de ' . self::MAX_UPLOAD_FILE / 1000000 . 'Mo.';
            }
            if (!empty($file['tmp_name'])) {
                if (!in_array(mime_content_type($file['tmp_name']), self::ALLOWED_MIMES)) {
                    $errors[] = 'Le fichier doit être de type ' . implode(', ', self::ALLOWED_MIMES);
                }
            } else {
                $errors[] = 'Pas de photo sélectionnée';
            }
        }
        return $errors;
    }

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $propertyManager = new PropertyManager();
            $propertyManager->delete($id);

            header('Location: /admin');
        }
    }
}
