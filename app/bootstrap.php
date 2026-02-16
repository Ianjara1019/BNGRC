<?php

// Bootstrap de l'application BNGRC - Gestion des Dons

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\DashboardController;
use App\Controllers\BesoinController;
use App\Controllers\DonController;
use App\Controllers\DistributionController;

// Configuration de Flight
Flight::set('flight.views.path', __DIR__ . '/views');

// Configuration du layout personnalisé
Flight::map('render', function($template, $data = []) {
    extract($data);
    ob_start();
    include __DIR__ . '/views/' . $template . '.php';
    $content = ob_get_clean();

    Flight::view()->set('body', function() use ($content) {
        return $content;
    });

    Flight::view()->set('title', $data['title'] ?? 'BNGRC - Gestion des Dons');

    $body = Flight::view()->get('body');
    $title = Flight::view()->get('title');
    extract(['body' => $body, 'title' => $title]);
    include __DIR__ . '/views/layout.php';
});