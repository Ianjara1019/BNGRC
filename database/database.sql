-- Base de données pour BNGRC - Gestion des Dons
CREATE DATABASE IF NOT EXISTS bngrc_dons;
USE bngrc_dons;

CREATE TABLE villes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    region VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE types_besoins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categorie ENUM('nature', 'materiel', 'argent') NOT NULL,
    nom VARCHAR(100) NOT NULL,
    unite VARCHAR(50) NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY categorie_nom (categorie, nom)
);

CREATE TABLE besoins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ville_id INT NOT NULL,
    type_besoin_id INT NOT NULL,
    quantite DECIMAL(10, 2) NOT NULL,
    quantite_recue DECIMAL(10, 2) DEFAULT 0,
    statut ENUM('en_attente', 'partiel', 'satisfait') DEFAULT 'en_attente',
    date_saisie TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE,
    FOREIGN KEY (type_besoin_id) REFERENCES types_besoins(id) ON DELETE CASCADE
);

CREATE TABLE dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_besoin_id INT NOT NULL,
    quantite DECIMAL(10, 2) NOT NULL,
    quantite_restante DECIMAL(10, 2) NOT NULL,
    donateur VARCHAR(200),
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('disponible', 'distribue', 'partiel') DEFAULT 'disponible',
    FOREIGN KEY (type_besoin_id) REFERENCES types_besoins(id) ON DELETE CASCADE
);

CREATE TABLE distributions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite DECIMAL(10, 2) NOT NULL,
    date_distribution TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE CASCADE,
    FOREIGN KEY (besoin_id) REFERENCES besoins(id) ON DELETE CASCADE
);

INSERT INTO villes (nom, region) VALUES
('Antananarivo', 'Analamanga'),
('Toamasina', 'Atsinanana'),
('Antsirabe', 'Vakinankaratra'),
('Mahajanga', 'Boeny'),
('Fianarantsoa', 'Haute Matsiatra');

INSERT INTO types_besoins (categorie, nom, unite, prix_unitaire) VALUES
('nature', 'Riz', 'kg', 3000),
('nature', 'Huile', 'litre', 8000),
('nature', 'Sucre', 'kg', 4000),
('nature', 'Haricot', 'kg', 5000),
('nature', 'Farine', 'kg', 3500),
('materiel', 'Tôle', 'unité', 25000),
('materiel', 'Clou', 'kg', 8000),
('materiel', 'Bois', 'planche', 15000),
('materiel', 'Bâche', 'unité', 20000),
('materiel', 'Couverture', 'unité', 12000),
('argent', 'Don en argent', 'Ariary', 1);

INSERT INTO besoins (ville_id, type_besoin_id, quantite) VALUES
(1, 1, 500), (1, 2, 100), (1, 6, 200), (1, 11, 5000000),
(2, 1, 800), (2, 3, 150), (2, 6, 300), (2, 7, 50),
(3, 1, 300), (3, 10, 100), (3, 11, 2000000),
(4, 1, 600), (4, 2, 120), (4, 9, 80),
(5, 1, 400), (5, 5, 200), (5, 6, 150);

INSERT INTO dons (type_besoin_id, quantite, quantite_restante, donateur) VALUES
(1, 1000, 1000, 'ONG Caritas'),
(2, 200, 200, 'Donateur anonyme'),
(6, 500, 500, 'Entreprise BTP'),
(11, 10000000, 10000000, 'Collecte publique');