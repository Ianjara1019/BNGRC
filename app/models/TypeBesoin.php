<?php

namespace App\Models;

use PDO;

class TypeBesoin {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM types_besoins ORDER BY categorie, nom");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM types_besoins WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByCategorie($categorie) {
        $stmt = $this->db->prepare("SELECT * FROM types_besoins WHERE categorie = ? ORDER BY nom");
        $stmt->execute([$categorie]);
        return $stmt->fetchAll();
    }
    
    public function create($categorie, $nom, $unite, $prixUnitaire) {
        $stmt = $this->db->prepare("
            INSERT INTO types_besoins (categorie, nom, unite, prix_unitaire) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$categorie, $nom, $unite, $prixUnitaire]);
    }
}
