<?php

namespace App\Controllers;

use App\Models\Ville;
use App\Models\Besoin;
use App\Models\Don;
use App\Models\Distribution;
use Flight;

class DashboardController {
    
    public function index() {
        $villeModel = new Ville();
        $besoinModel = new Besoin();
        $donModel = new Don();
        
        $villes = $villeModel->getWithBesoins();
        
        $tableauBord = [];
        foreach ($villes as $ville) {
            $besoins = $besoinModel->getByVille($ville['id']);
            $distributions = (new Distribution())->getByVille($ville['id']);
            
            $tableauBord[] = [
                'ville' => $ville,
                'besoins' => $besoins,
                'distributions' => $distributions
            ];
        }
        
        $stats = [
            'total_besoins' => count($besoinModel->getAll()),
            'total_dons' => count($donModel->getAll()),
            'total_distributions' => count((new Distribution())->getAll()),
            'total_villes' => count($villes)
        ];
        
        Flight::render('dashboard', [
            'tableauBord' => $tableauBord,
            'stats' => $stats,
            'title' => 'Tableau de bord'
        ]);
    }
}
