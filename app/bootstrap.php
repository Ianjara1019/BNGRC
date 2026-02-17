<?php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

use App\Controllers\DashboardController;
use App\Controllers\BesoinController;
use App\Controllers\DonController;
use App\Controllers\DistributionController;

// Configuration de Flight
Flight::set('flight.views.path', __DIR__ . '/views');
Flight::set('flight.base_url', $config['app']['base_url']);

// Helper URL
function url($path = '') {
    $baseUrl = Flight::get('flight.base_url');
    return $baseUrl . '/' . ltrim($path, '/');
}

// Render avec layout
Flight::map('render', function($template, $data = []) {
    extract($data);

    ob_start();
    include __DIR__ . '/views/' . $template . '.php';
    $content = ob_get_clean();

    $layoutData = [
        'body' => $content,
        'title' => $data['title'] ?? 'BNGRC - Gestion des Dons'
    ];
    extract($layoutData);

    include __DIR__ . '/views/layout.php';
});
