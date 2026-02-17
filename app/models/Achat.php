<?php

namespace App\Models;

use PDO;

class Achat {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($villeId = null) {
        $query = "
            SELECT
                a.*,
                b.ville_id,
                v.nom as ville_nom,
                tb.nom as type_nom,
                tb.categorie,
                tb.unite,
                tb.prix_unitaire,
                (a.quantite * tb.prix_unitaire) as valeur_achat,
                (a.quantite * tb.prix_unitaire * (1 + a.frais_percent / 100)) as valeur_totale_avec_frais,
                d.donateur as source_don
            FROM achats a
            JOIN besoins b ON a.besoin_id = b.id
            JOIN villes v ON b.ville_id = v.id
            JOIN types_besoins tb ON b.type_besoin_id = tb.id
            JOIN dons d ON a.don_argent_id = d.id
            WHERE a.statut = 'valide'
        ";

        $params = [];
        if ($villeId) {
            $query .= " AND b.ville_id = ?";
            $params[] = $villeId;
        }

        $query .= " ORDER BY a.date_achat DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($besoinId, $donArgentId, $quantite, $fraisPercent) {
        $stmt = $this->db->prepare("
            INSERT INTO achats (besoin_id, don_argent_id, quantite, frais_percent, statut)
            VALUES (?, ?, ?, ?, 'simulation')
        ");
        return $stmt->execute([$besoinId, $donArgentId, $quantite, $fraisPercent]);
    }

    public function validerAchat($id) {
        try {
            $this->db->beginTransaction();

            // Récupérer les détails de l'achat
            $achat = $this->getById($id);
            if (!$achat || $achat['statut'] !== 'simulation') {
                throw new \Exception('Achat non trouvé ou déjà validé');
            }

            $montantTotal = $achat['quantite'] * $achat['prix_unitaire'] * (1 + $achat['frais_percent'] / 100);

            // Récupérer tous les dons en argent disponibles
            $donModel = new Don();
            $donsArgent = $donModel->getDonsArgentDisponibles();

            // Calculer le total disponible
            $totalDisponible = 0;
            foreach ($donsArgent as $don) {
                $totalDisponible += $don['quantite_restante'];
            }

            if ($totalDisponible < $montantTotal) {
                throw new \Exception('Fonds insuffisants. Total disponible: ' . number_format($totalDisponible, 0) . ' Ar');
            }

            // Répartir le montant sur les dons disponibles
            $montantRestant = $montantTotal;
            $distributions = [];

            foreach ($donsArgent as $don) {
                if ($montantRestant <= 0) break;

                $montantDuDon = min($montantRestant, $don['quantite_restante']);
                if ($montantDuDon > 0) {
                    $distributions[] = [
                        'don_id' => $don['id'],
                        'montant' => $montantDuDon
                    ];
                    $montantRestant -= $montantDuDon;
                }
            }

            // Mettre à jour le statut de l'achat
            $stmt = $this->db->prepare("UPDATE achats SET statut = 'valide' WHERE id = ?");
            $stmt->execute([$id]);

            // Mettre à jour le besoin (quantité reçue)
            $besoinModel = new Besoin();
            $besoinModel->updateQuantiteRecue($achat['besoin_id'], $achat['quantite']);

            // Créer les distributions et mettre à jour les dons
            $distributionModel = new Distribution();
            foreach ($distributions as $dist) {
                // Créer une distribution pour chaque don utilisé
                $distributionModel->create($dist['don_id'], $achat['besoin_id'], $achat['quantite'] * ($dist['montant'] / $montantTotal));

                // Mettre à jour le don en argent (quantité restante)
                $donModel->updateQuantiteRestante($dist['don_id'], $dist['montant']);
            }

            $this->db->commit();
            return ['success' => true];

        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getById($id) {
        $query = "
            SELECT
                a.*,
                b.ville_id,
                tb.prix_unitaire
            FROM achats a
            JOIN besoins b ON a.besoin_id = b.id
            JOIN types_besoins tb ON b.type_besoin_id = tb.id
            WHERE a.id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAchatsSimulation() {
        $query = "
            SELECT
                a.*,
                b.ville_id,
                v.nom as ville_nom,
                tb.nom as type_nom,
                tb.categorie,
                tb.unite,
                tb.prix_unitaire,
                (a.quantite * tb.prix_unitaire) as valeur_achat,
                (a.quantite * tb.prix_unitaire * (1 + a.frais_percent / 100)) as valeur_totale_avec_frais,
                d.donateur as source_don
            FROM achats a
            JOIN besoins b ON a.besoin_id = b.id
            JOIN villes v ON b.ville_id = v.id
            JOIN types_besoins tb ON b.type_besoin_id = tb.id
            JOIN dons d ON a.don_argent_id = d.id
            WHERE a.statut = 'simulation'
            ORDER BY a.date_achat DESC
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }

    public function supprimerSimulation($id) {
        $stmt = $this->db->prepare("DELETE FROM achats WHERE id = ? AND statut = 'simulation'");
        return $stmt->execute([$id]);
    }

    public function getRecapitulatif() {
        $query = "
            SELECT
                SUM(b.quantite * tb.prix_unitaire) as besoins_totaux,
                SUM(b.quantite_recue * tb.prix_unitaire) as besoins_satisfaits,
                SUM((b.quantite - b.quantite_recue) * tb.prix_unitaire) as besoins_restants
            FROM besoins b
            JOIN types_besoins tb ON b.type_besoin_id = tb.id
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetch();
    }
}