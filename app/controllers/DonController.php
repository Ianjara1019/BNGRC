<?php

namespace App\Controllers;

use App\Models\Don;
use App\Models\TypeBesoin;
use Flight;

class DonController {
    
    public function index() {
        $donModel = new Don();
        $dons = $donModel->getAll();
        
        Flight::render('dons/index', [
            'dons' => $dons,
            'title' => 'Liste des dons'
        ]);
    }
    
    public function create() {
        $typeBesoinModel = new TypeBesoin();
        $typesBesoins = $typeBesoinModel->getAll();
        
        Flight::render('dons/create', [
            'typesBesoins' => $typesBesoins,
            'title' => 'Enregistrer un don'
        ]);
    }
    
    public function store() {
        $typeBesoinId = Flight::request()->data->type_besoin_id;
        $quantite = Flight::request()->data->quantite;
        $donateur = Flight::request()->data->donateur ?? 'Anonyme';
        
        if (!$typeBesoinId || !$quantite || $quantite <= 0) {
            Flight::json(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }
        
        $donModel = new Don();
        $result = $donModel->create($typeBesoinId, $quantite, $donateur);
        
        if ($result) {
            Flight::json(['success' => true, 'message' => 'Don enregistré avec succès']);
        } else {
            Flight::json(['success' => false, 'message' => 'Erreur lors de l\'enregistrement'], 500);
        }
    }
}
