<?php

namespace App\Models;

use PDO;

class Besoin {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $query = "
            SELECT 
                b.*,
                v.nom as ville_nom,
                tb.nom as type_nom,
                tb.categorie,
                tb.unite,
                tb.prix_unitaire,
                (b.quantite * tb.prix_unitaire) as valeur_totale,
                (b.quantite_recue * tb.prix_unitaire) as valeur_recue
            FROM besoins b
            JOIN villes v ON b.ville_id = v.id
            JOIN types_besoins tb ON b.type_besoin_id = tb.id
            ORDER BY b.date_saisie DESC
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
    
    public function getByVille($villeId) {
        $query = "
            SELECT 
                b.*,
                tb.nom as type_nom,
                tb.categorie,
                tb.unite,
                tb.prix_unitaire,
                (b.quantite - b.quantite_recue) as quantite_manquante,
                (b.quantite * tb.prix_unitaire) as valeur_totale,
                (b.quantite_recue * tb.prix_unitaire) as valeur_recue
            FROM besoins b
            JOIN types_besoins tb ON b.type_besoin_id = tb.id
            WHERE b.ville_id = ?
            ORDER BY b.date_saisie DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$villeId]);
        return $stmt->fetchAll();
    }
    
    public function create($villeId, $typeBesoinId, $quantite) {
        $stmt = $this->db->prepare("
            INSERT INTO besoins (ville_id, type_besoin_id, quantite) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$villeId, $typeBesoinId, $quantite]);
    }
    
    public function updateQuantiteRecue($id, $quantite) {
        $stmt = $this->db->prepare("
            UPDATE besoins 
            SET quantite_recue = quantite_recue + ?,
                statut = CASE 
                    WHEN quantite_recue + ? >= quantite THEN 'satisfait'
                    WHEN quantite_recue + ? > 0 THEN 'partiel'
                    ELSE 'en_attente'
                END
            WHERE id = ?
        ");
        return $stmt->execute([$quantite, $quantite, $quantite, $id]);
    }
    
    public function getBesoinsNonSatisfaits() {
        $query = "
            SELECT 
                b.*,
                v.nom as ville_nom,
                tb.nom as type_nom,
                tb.categorie,
                tb.unite,
                tb.prix_unitaire,
                (b.quantite - b.quantite_recue) as quantite_manquante
            FROM besoins b
            JOIN villes v ON b.ville_id = v.id
            JOIN types_besoins tb ON b.type_besoin_id = tb.id
            WHERE b.statut != 'satisfait'
            ORDER BY b.date_saisie ASC
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
}
