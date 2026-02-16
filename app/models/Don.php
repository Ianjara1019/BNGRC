<?php

namespace App\Models;

use PDO;

class Don {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $query = "
            SELECT 
                d.*,
                tb.nom as type_nom,
                tb.categorie,
                tb.unite,
                tb.prix_unitaire,
                (d.quantite * tb.prix_unitaire) as valeur_totale,
                (d.quantite_restante * tb.prix_unitaire) as valeur_restante
            FROM dons d
            JOIN types_besoins tb ON d.type_besoin_id = tb.id
            ORDER BY d.date_don DESC
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $query = "
            SELECT 
                d.*,
                tb.nom as type_nom,
                tb.categorie,
                tb.unite,
                tb.prix_unitaire
            FROM dons d
            JOIN types_besoins tb ON d.type_besoin_id = tb.id
            WHERE d.id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($typeBesoinId, $quantite, $donateur = null) {
        $stmt = $this->db->prepare("
            INSERT INTO dons (type_besoin_id, quantite, quantite_restante, donateur) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$typeBesoinId, $quantite, $quantite, $donateur]);
    }
    
    public function updateQuantiteRestante($id, $quantite) {
        $stmt = $this->db->prepare("
            UPDATE dons 
            SET quantite_restante = quantite_restante - ?,
                statut = CASE 
                    WHEN quantite_restante - ? <= 0 THEN 'distribue'
                    WHEN quantite_restante - ? < quantite THEN 'partiel'
                    ELSE 'disponible'
                END
            WHERE id = ?
        ");
        return $stmt->execute([$quantite, $quantite, $quantite, $id]);
    }
    
    public function getDonsDisponibles() {
        $query = "
            SELECT 
                d.*,
                tb.nom as type_nom,
                tb.categorie,
                tb.unite,
                tb.prix_unitaire
            FROM dons d
            JOIN types_besoins tb ON d.type_besoin_id = tb.id
            WHERE d.quantite_restante > 0
            ORDER BY d.date_don ASC
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
}
