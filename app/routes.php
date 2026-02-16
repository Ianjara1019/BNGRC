<?php

use App\Controllers\DashboardController;
use App\Controllers\BesoinController;
use App\Controllers\DonController;
use App\Controllers\DistributionController;

// Définition des routes de l'application

// Page d'accueil - Tableau de bord
Flight::route('/', function() {
    $controller = new DashboardController();
    $controller->index();
});

// Routes pour les besoins
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

// Routes pour les dons
Flight::route('GET /dons/create', function() {
    $controller = new DonController();
    $controller->create();
});

Flight::route('POST /dons', function() {
    $controller = new DonController();
    $controller->store();
});

// Routes pour les distributions
Flight::route('GET /distributions', function() {
    $controller = new DistributionController();
    $controller->index();
});

Flight::route('POST /distributions/dispatch', function() {
    $controller = new DistributionController();
    $controller->executerDispatch();
});