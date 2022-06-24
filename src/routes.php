<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['HomeController', 'index',],
    'home/index' => ['HomeController', 'index',],
    'property/index' => ['PropertyController', 'index',],
    'property/show' => ['PropertyController', 'show', ['id']],
    'contact/index' => ['ContactController', 'index', ['id']],
    'admin' => ['AdminAdvertisementController', 'index',],
    'admin/add' => ['AdminAdvertisementController', 'add',],
    'admin/delete' => ['AdminAdvertisementController', 'delete', ['id']],
];
