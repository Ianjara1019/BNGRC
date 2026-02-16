<?php

use App\Controllers\DashboardController;
use App\Controllers\BesoinController;
use App\Controllers\DonController;
use App\Controllers\DistributionController;

Flight::route('/', function() {
    $controller = new DashboardController();
    $controller->index();
});

Flight::route('GET /besoins/create', function() {
    $controller = new BesoinController();
    $controller->create();
});

Flight::route('POST /besoins', function() {
    $controller = new BesoinController();
    $controller->store();
});

Flight::route('GET /besoins/ville/@id', function($id) {
    $controller = new BesoinController();
    $controller->getByVille($id);
});

Flight::route('GET /dons/create', function() {
    $controller = new DonController();
    $controller->create();
});

Flight::route('POST /dons', function() {
    $controller = new DonController();
    $controller->store();
});

Flight::route('GET /distributions', function() {
    $controller = new DistributionController();
    $controller->index();
});

Flight::route('POST /distributions/dispatch', function() {
    $controller = new DistributionController();
    $controller->executerDispatch();
});