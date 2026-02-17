<?php

use App\Controllers\DashboardController;
use App\Controllers\BesoinController;
use App\Controllers\DonController;
use App\Controllers\DistributionController;
use App\Controllers\AchatController;

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

Flight::route('GET /achats', function() {
    $controller = new AchatController();
    $controller->index();
});

Flight::route('GET /achats/simulation', function() {
    $controller = new AchatController();
    $controller->simulation();
});

Flight::route('POST /achats', function() {
    $controller = new AchatController();
    $controller->store();
});

Flight::route('POST /achats/valider', function() {
    $controller = new AchatController();
    $controller->valider();
});

Flight::route('DELETE /achats/simulation', function() {
    $controller = new AchatController();
    $controller->supprimerSimulation();
});

Flight::route('GET /achats/recapitulatif', function() {
    $controller = new AchatController();
    $controller->recapitulatifPage();
});

Flight::route('POST /reset-data', function() {
    $controller = new DashboardController();
    $controller->resetData();
});