<?php

namespace App\Controller;

use App\Model\PhotoManager;
use App\Model\PropertyManager;
use App\Model\HomeCarouselManager;

class HomeController extends AbstractController
{
    public function index()
    {
        $photos = [];
        $properties = [];
        $photoManager = new PhotoManager();
        $propertyManager = new PropertyManager();
        /*Retrieve the last 3 houses for sale opr rent fed into the database
        to be displayed on homepage-based 3-fold eye-catcher slider */
        foreach ($photoManager->selectLastProperties() as $index => $property) {
            $photos[$index] = $photoManager->selectByPropertyId($property["id"]);
            $properties[$index ] = $propertyManager->selectHomeSliderInfo($property["id"]);
        }
        return $this->twig->render('Home/index.html.twig', ['photos' => $photos, 'properties' => $properties]);
    }
}
