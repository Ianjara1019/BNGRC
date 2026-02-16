<?php

// Point d'entrée de l'application BNGRC - Gestion des Dons

require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/../app/routes.php';

// Démarrer l'application
Flight::start();
