<?php

namespace App\Models;

use PDO;

class Distribution {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($donId, $besoinId, $quantite) {
        $stmt = $this->db->prepare("
            INSERT INTO distributions (don_id, besoin_id, quantite) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$donId, $besoinId, $quantite]);
    }
    
    public function getAll() {
        $query = "
            SELECT 
                dist.*,
                d.donateur,
                v.nom as ville_nom,
                tb.nom as type_nom,
                tb.unite
            FROM distributions dist
            JOIN dons d ON dist.don_id = d.id
            JOIN besoins b ON dist.besoin_id = b.id
            JOIN villes v ON b.ville_id = v.id
            JOIN types_besoins tb ON b.type_besoin_id = tb.id
            ORDER BY dist.date_distribution DESC
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
    
    public function getByVille($villeId) {
        $query = "
            SELECT 
                dist.*,
                d.donateur,
                tb.nom as type_nom,
                tb.unite,
                tb.prix_unitaire,
                (dist.quantite * tb.prix_unitaire) as valeur
            FROM distributions dist
            JOIN dons d ON dist.don_id = d.id
            JOIN besoins b ON dist.besoin_id = b.id
            JOIN types_besoins tb ON b.type_besoin_id = tb.id
            WHERE b.ville_id = ?
            ORDER BY dist.date_distribution DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$villeId]);
        return $stmt->fetchAll();
    }
    
    public function executerDispatch($mode = 'date') {
        try {
            $this->db->beginTransaction();
            
            $besoinModel = new Besoin();
            $donModel = new Don();
            
            $besoins = $besoinModel->getBesoinsNonSatisfaits($mode);
            $dons = $donModel->getDonsDisponibles();
            
            $distributionsEffectuees = 0;
            
            foreach ($besoins as $besoin) {
                $quantiteManquante = $besoin['quantite_manquante'];
                
                foreach ($dons as &$don) {
                    if ($don['type_besoin_id'] == $besoin['type_besoin_id'] && $don['quantite_restante'] > 0) {
                        $quantiteADistribuer = min($quantiteManquante, $don['quantite_restante']);
                        
                        $this->create($don['id'], $besoin['id'], $quantiteADistribuer);
                        
                        $besoinModel->updateQuantiteRecue($besoin['id'], $quantiteADistribuer);
                        $besoinModel->recalculerStatut($besoin['id']);
                        $donModel->updateQuantiteRestante($don['id'], $quantiteADistribuer);
                        
                        $don['quantite_restante'] -= $quantiteADistribuer;
                        $quantiteManquante -= $quantiteADistribuer;
                        
                        $distributionsEffectuees++;
                        
                        if ($quantiteManquante <= 0) {
                            break;
                        }
                    }
                }
            }
            
            $this->db->commit();
            return ['success' => true, 'distributions' => $distributionsEffectuees];
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
