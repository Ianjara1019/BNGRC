<?php

// Point d'entrée de l'application BNGRC - Gestion des Dons

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/../app/routes.php';

// Démarrer l'application
Flight::start();
