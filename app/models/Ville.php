<?php

namespace App\Models;

use PDO;

class Ville {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM villes ORDER BY nom");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM villes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($nom, $region) {
        $stmt = $this->db->prepare("INSERT INTO villes (nom, region) VALUES (?, ?)");
        return $stmt->execute([$nom, $region]);
    }
    
    public function getWithBesoins() {
        $query = "
            SELECT 
                v.id,
                v.nom,
                v.region,
                COUNT(DISTINCT b.id) as total_besoins,
                SUM(b.quantite * tb.prix_unitaire) as valeur_totale_besoins,
                SUM(b.quantite_recue * tb.prix_unitaire) as valeur_recue
            FROM villes v
            LEFT JOIN besoins b ON v.id = b.ville_id
            LEFT JOIN types_besoins tb ON b.type_besoin_id = tb.id
            GROUP BY v.id, v.nom, v.region
            ORDER BY v.nom
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
}
