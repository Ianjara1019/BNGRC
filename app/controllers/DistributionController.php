<?php

namespace App\Controllers;

use App\Models\Distribution;
use Flight;

class DistributionController {
    
    public function index() {
        $distributionModel = new Distribution();
        $distributions = $distributionModel->getAll();
        
        Flight::render('distributions/index', [
            'distributions' => $distributions,
            'title' => 'Historique des distributions'
        ]);
    }
    
    public function executerDispatch() {
        $distributionModel = new Distribution();
        $result = $distributionModel->executerDispatch();
        
        if ($result['success']) {
            Flight::json([
                'success' => true, 
                'message' => "Dispatch exécuté avec succès. {$result['distributions']} distribution(s) effectuée(s).",
                'distributions' => $result['distributions']
            ]);
        } else {
            Flight::json([
                'success' => false, 
                'message' => 'Erreur lors du dispatch: ' . $result['error']
            ], 500);
        }
    }
}
