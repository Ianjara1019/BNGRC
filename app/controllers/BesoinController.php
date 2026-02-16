<?php

namespace App\Controllers;

use App\Models\Besoin;
use App\Models\Ville;
use App\Models\TypeBesoin;
use Flight;

class BesoinController {
    
    public function index() {
        $besoinModel = new Besoin();
        $besoins = $besoinModel->getAll();
        
        Flight::render('besoins/index', [
            'besoins' => $besoins,
            'title' => 'Liste des besoins'
        ]);
    }
    
    public function create() {
        $villeModel = new Ville();
        $typeBesoinModel = new TypeBesoin();
        
        $villes = $villeModel->getAll();
        $typesBesoins = $typeBesoinModel->getAll();
        
        Flight::render('besoins/create', [
            'villes' => $villes,
            'typesBesoins' => $typesBesoins,
            'title' => 'Saisir un besoin'
        ]);
    }
    
    public function store() {
        $villeId = Flight::request()->data->ville_id;
        $typeBesoinId = Flight::request()->data->type_besoin_id;
        $quantite = Flight::request()->data->quantite;
        
        if (!$villeId || !$typeBesoinId || !$quantite || $quantite <= 0) {
            Flight::json(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }
        
        $besoinModel = new Besoin();
        $result = $besoinModel->create($villeId, $typeBesoinId, $quantite);
        
        if ($result) {
            Flight::json(['success' => true, 'message' => 'Besoin enregistré avec succès']);
        } else {
            Flight::json(['success' => false, 'message' => 'Erreur lors de l\'enregistrement'], 500);
        }
    }
    
    public function getByVille($villeId) {
        $besoinModel = new Besoin();
        $besoins = $besoinModel->getByVille($villeId);
        
        Flight::json(['success' => true, 'data' => $besoins]);
    }
}
