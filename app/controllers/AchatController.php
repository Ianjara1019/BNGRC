<?php

namespace App\Controllers;

use App\Models\Achat;
use App\Models\Besoin;
use App\Models\Don;
use App\Models\Ville;
use Flight;

class AchatController {

    public function index() {
        $villeId = Flight::request()->query['ville_id'] ?? null;

        $achatModel = new Achat();
        $villeModel = new Ville();

        $achats = $achatModel->getAll($villeId);
        $villes = $villeModel->getAll();

        Flight::render('achats/index', [
            'achats' => $achats,
            'villes' => $villes,
            'villeFiltre' => $villeId,
            'title' => 'Historique des achats'
        ]);
    }

    public function simulation() {
        $besoinModel = new Besoin();
        $donModel = new Don();
        $achatModel = new Achat();

        $besoins = $besoinModel->getBesoinsNonSatisfaits();
        $donsArgent = $donModel->getDonsArgentDisponibles();
        $achatsSimulation = $achatModel->getAchatsSimulation();

        // Calculer le total des fonds disponibles
        $totalFondsDisponibles = 0;
        foreach ($donsArgent as $don) {
            $totalFondsDisponibles += $don['quantite_restante'];
        }

        Flight::render('achats/simulation', [
            'besoins' => $besoins,
            'donsArgent' => $donsArgent,
            'achatsSimulation' => $achatsSimulation,
            'totalFondsDisponibles' => $totalFondsDisponibles,
            'title' => 'Simulation des achats'
        ]);
    }

    public function store() {
        $besoinId = Flight::request()->data->besoin_id;
        $quantite = Flight::request()->data->quantite;
        $fraisPercent = Flight::request()->data->frais_percent ?? Flight::get('config')['app']['frais_achat_percent'];

        if (!$besoinId || !$quantite || $quantite <= 0) {
            Flight::json(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        // Vérifier que le besoin existe et n'est pas déjà satisfait
        $besoinModel = new Besoin();
        $besoin = $besoinModel->getById($besoinId);
        if (!$besoin) {
            Flight::json(['success' => false, 'message' => 'Besoin non trouvé'], 404);
            return;
        }

        if ($besoin['statut'] === 'satisfait') {
            Flight::json(['success' => false, 'message' => 'Ce besoin est déjà satisfait'], 400);
            return;
        }

        // Calculer le montant total nécessaire
        $montantTotal = $quantite * $besoin['prix_unitaire'] * (1 + $fraisPercent / 100);

        // Récupérer tous les dons en argent disponibles
        $donModel = new Don();
        $donsArgent = $donModel->getDonsArgentDisponibles();

        // Calculer le total disponible
        $totalDisponible = 0;
        foreach ($donsArgent as $don) {
            $totalDisponible += $don['quantite_restante'];
        }

        if ($totalDisponible < $montantTotal) {
            Flight::json(['success' => false, 'message' => 'Fonds insuffisants. Total disponible: ' . number_format($totalDisponible, 0) . ' Ar, requis: ' . number_format($montantTotal, 0) . ' Ar'], 400);
            return;
        }

        // Créer l'achat en utilisant le premier don disponible (on utilisera la logique de répartition lors de la validation)
        // Pour la simulation, on utilise simplement le premier don disponible
        $premierDon = $donsArgent[0];

        $achatModel = new Achat();
        $result = $achatModel->create($besoinId, $premierDon['id'], $quantite, $fraisPercent);

        if ($result) {
            Flight::json(['success' => true, 'message' => 'Achat ajouté à la simulation']);
        } else {
            Flight::json(['success' => false, 'message' => 'Erreur lors de l\'ajout à la simulation'], 500);
        }
    }

    public function valider() {
        $achatId = Flight::request()->data->achat_id;

        if (!$achatId) {
            Flight::json(['success' => false, 'message' => 'ID d\'achat manquant'], 400);
            return;
        }

        $achatModel = new Achat();
        $result = $achatModel->validerAchat($achatId);

        if ($result['success']) {
            Flight::json(['success' => true, 'message' => 'Achat validé avec succès']);
        } else {
            Flight::json(['success' => false, 'message' => $result['error']], 500);
        }
    }

    public function supprimerSimulation() {
        $achatId = Flight::request()->data->achat_id;

        if (!$achatId) {
            Flight::json(['success' => false, 'message' => 'ID d\'achat manquant'], 400);
            return;
        }

        $achatModel = new Achat();
        $result = $achatModel->supprimerSimulation($achatId);

        if ($result) {
            Flight::json(['success' => true, 'message' => 'Achat supprimé de la simulation']);
        } else {
            Flight::json(['success' => false, 'message' => 'Erreur lors de la suppression'], 500);
        }
    }

    public function recapitulatif() {
        $achatModel = new Achat();
        $recap = $achatModel->getRecapitulatif();

        Flight::json([
            'success' => true,
            'data' => $recap
        ]);
    }

    public function recapitulatifPage() {
        Flight::render('achats/recapitulatif', [
            'title' => 'Récapitulatif des besoins'
        ]);
    }
}