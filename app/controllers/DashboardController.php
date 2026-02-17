<?php

namespace App\Controllers;

use App\Models\Ville;
use App\Models\Besoin;
use App\Models\Don;
use App\Models\Distribution;
use App\Models\Database;
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
    
    public function resetData() {
        $db = null;
        try {
            $db = Database::getInstance()->getConnection();
            
            // Supprimer toutes les données dans l'ordre inverse des dépendances
            $tables = ['achats', 'distributions', 'dons', 'besoins', 'types_besoins', 'villes'];
            foreach ($tables as $table) {
                try {
                    $db->exec("DELETE FROM $table");
                } catch (\Exception $deleteException) {
                    // La table n'existe peut-être pas, continuer
                }
            }
            
            // Réinitialiser les auto-increments (optionnel)
            foreach ($tables as $table) {
                try {
                    $db->exec("ALTER TABLE $table AUTO_INCREMENT = 1");
                } catch (\Exception $alterException) {
                    // Les ALTER TABLE peuvent échouer si les tables n'existent pas encore
                }
            }
            
            // Recharger les données de test
            $sqlFile = __DIR__ . '/../../database/database.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                
                // Diviser le SQL en statements individuels
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                
                foreach ($statements as $statement) {
                    if (!empty($statement) && !preg_match('/^(CREATE|USE|DROP)/i', $statement)) {
                        try {
                            $db->exec($statement);
                        } catch (\Exception $insertException) {
                            // Certaines insertions peuvent échouer, continuer
                        }
                    }
                }
            }
            
            Flight::json(['success' => true, 'message' => 'Données réinitialisées avec succès']);
            
        } catch (\Exception $e) {
            Flight::json(['success' => false, 'message' => 'Erreur lors de la réinitialisation: ' . $e->getMessage()], 500);
        }
    }
}
