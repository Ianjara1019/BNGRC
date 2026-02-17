<!-- Nom du donateur -->
<div class="mb-4">
    <label for="donateur" class="form-label fw-bold">
        <i class="bi bi-person-fill text-primary me-2"></i>
        Nom du donateur
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="bi bi-person"></i>
        </span>
        <input type="text" class="form-control form-control-lg" id="donateur" name="donateur"
               placeholder="Votre nom ou 'Anonyme'">
    </div>
    <div class="form-text">
        <small class="text-muted">Laissez vide pour un don anonyme</small>
    </div>
</div>

<!-- Type de don -->
<div class="mb-4">
    <label for="type_besoin_id" class="form-label fw-bold">
        <i class="bi bi-tag-fill text-success me-2"></i>
        Type de don *
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="bi bi-list-check"></i>
        </span>
        <select class="form-select form-select-lg" id="type_besoin_id" name="type_besoin_id" required>
            <option value="">-- Sélectionnez le type de don --</option>
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
        <small class="text-muted">Choisissez le type de don que vous souhaitez faire</small>
    </div>
</div>

<!-- Quantité -->
<div class="mb-4">
    <label for="quantite" class="form-label fw-bold">
        <i class="bi bi-calculator-fill text-info me-2"></i>
        Quantité *
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="bi bi-hash"></i>
        </span>
        <input type="number" class="form-control form-control-lg" id="quantite" name="quantite"
               step="0.01" min="0.01" required placeholder="Ex: 50">
        <span class="input-group-text bg-light" id="uniteAffichage">
            <small class="text-muted">--</small>
        </span>
    </div>
    <div class="form-text">
        <small class="text-muted" id="aideQuantite">Saisissez la quantité que vous souhaitez donner</small>
    </div>
</div>

<!-- Résumé du don -->
<div class="mb-4" id="resumeSection" style="display: none;">
    <div class="card bg-light border-0">
        <div class="card-body">
            <h6 class="card-title text-success">
                <i class="bi bi-gift me-2"></i>Résumé de votre don
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-white rounded">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cash text-success fs-4 me-3"></i>
                            <div>
                                <div class="fw-bold text-success" id="valeurTotale">0 Ar</div>
                                <small class="text-muted">Valeur totale du don</small>
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
            <div class="mt-3 p-3 bg-success bg-opacity-10 rounded">
                <div class="d-flex align-items-center">
                    <i class="bi bi-heart-fill text-success fs-4 me-3"></i>
                    <div>
                        <div class="fw-bold text-success">Impact de votre don</div>
                        <small id="impactMessage" class="text-muted">Votre générosité va aider de nombreuses personnes</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>