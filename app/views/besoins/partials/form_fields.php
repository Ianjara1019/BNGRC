<!-- Sélection de la ville -->
<div class="mb-4">
    <label for="ville_id" class="form-label fw-bold">
        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
        Ville concernée *
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="bi bi-building"></i>
        </span>
        <select class="form-select form-select-lg" id="ville_id" name="ville_id" required>
            <option value="">-- Sélectionnez une ville --</option>
            <?php foreach ($villes as $ville): ?>
                <option value="<?= $ville['id'] ?>" data-region="<?= htmlspecialchars($ville['region']) ?>">
                    <?= htmlspecialchars($ville['nom']) ?> (<?= htmlspecialchars($ville['region']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-text">
        <small class="text-muted">Choisissez la ville où le besoin a été identifié</small>
    </div>
</div>

<!-- Sélection du type de besoin -->
<div class="mb-4">
    <label for="type_besoin_id" class="form-label fw-bold">
        <i class="bi bi-tag-fill text-success me-2"></i>
        Type de besoin *
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="bi bi-list-check"></i>
        </span>
        <select class="form-select form-select-lg" id="type_besoin_id" name="type_besoin_id" required>
            <option value="">-- Sélectionnez un type de besoin --</option>
            <?php
            $currentCategorie = '';
            foreach ($typesBesoins as $type):
                if ($currentCategorie != $type['categorie']):
                    if ($currentCategorie != '') echo '</optgroup>';
                    $currentCategorie = $type['categorie'];
                    $icon = $type['categorie'] === 'nature' ? 'egg-fried' : ($type['categorie'] === 'materiel' ? 'tools' : 'cash');
                    $color = $type['categorie'] === 'nature' ? 'success' : ($type['categorie'] === 'materiel' ? 'primary' : 'warning');
                    echo '<optgroup label="' . ucfirst($type['categorie']) . '" data-bs-icon="' . $icon . '" data-bs-color="' . $color . '">';
                endif;
            ?>
                <option value="<?= $type['id'] ?>"
                        data-unite="<?= htmlspecialchars($type['unite']) ?>"
                        data-prix="<?= $type['prix_unitaire'] ?>"
                        data-categorie="<?= $type['categorie'] ?>">
                    <?= htmlspecialchars($type['nom']) ?> (<?= htmlspecialchars($type['unite']) ?>)
                    - <?= number_format($type['prix_unitaire'], 0) ?> Ar
                </option>
            <?php endforeach; ?>
            <?php if ($currentCategorie != '') echo '</optgroup>'; ?>
        </select>
    </div>
    <div class="form-text">
        <small class="text-muted">Sélectionnez le type de besoin et son unité de mesure</small>
    </div>
</div>

<!-- Quantité -->
<div class="mb-4">
    <label for="quantite" class="form-label fw-bold">
        <i class="bi bi-calculator-fill text-info me-2"></i>
        Quantité requise *
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="bi bi-hash"></i>
        </span>
        <input type="number" class="form-control form-control-lg" id="quantite" name="quantite"
               step="0.01" min="0.01" required placeholder="Ex: 100">
        <span class="input-group-text bg-light" id="uniteAffichage">
            <small class="text-muted">--</small>
        </span>
    </div>
    <div class="form-text">
        <small class="text-muted" id="aideQuantite">Saisissez la quantité totale requise</small>
    </div>
</div>

<!-- Résumé et calculs -->
<div class="mb-4" id="resumeSection" style="display: none;">
    <div class="card bg-light border-0">
        <div class="card-body">
            <h6 class="card-title text-primary">
                <i class="bi bi-calculator me-2"></i>Résumé de la demande
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-white rounded">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cash text-success fs-4 me-3"></i>
                            <div>
                                <div class="fw-bold text-success" id="valeurTotale">0 Ar</div>
                                <small class="text-muted">Valeur totale estimée</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-white rounded">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle text-info fs-4 me-3"></i>
                            <div>
                                <div class="fw-bold text-info" id="prixUnitaire">0 Ar</div>
                                <small class="text-muted">Prix unitaire</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>