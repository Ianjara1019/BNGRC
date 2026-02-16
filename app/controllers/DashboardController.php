<?php

namespace App\Controllers;

use Flight;

class DashboardController {
    
    public function index() {
        Flight::render('dashboard', ['title' => 'Tableau de Bord - Gestion des Besoins et Dons'], 'layout');
    }
}
