<?php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

use App\Controllers\DashboardController;
use App\Controllers\BesoinController;
use App\Controllers\DonController;
use App\Controllers\DistributionController;

// Configuration de Flight
Flight::set('flight.views.path', __DIR__ . '/views');

// Détection automatique de base_url (plus flexible pour dev)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
Flight::set('flight.base_url', $protocol . '://' . $host);
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
