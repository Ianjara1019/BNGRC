<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4><i class="bi bi-exclamation-circle"></i> Saisir un besoin</h4>
            </div>
            <div class="card-body">
                <form id="formBesoin">
                    <div class="mb-3">
                        <label for="ville_id" class="form-label">Ville *</label>
                        <select class="form-select" id="ville_id" name="ville_id" required>
                            <option value="">-- Sélectionner une ville --</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id'] ?>">
                                    <?= htmlspecialchars($ville['nom']) ?> (<?= htmlspecialchars($ville['region']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="type_besoin_id" class="form-label">Type de besoin *</label>
                        <select class="form-select" id="type_besoin_id" name="type_besoin_id" required>
                            <option value="">-- Sélectionner un type --</option>
                            <?php 
                            $currentCategorie = '';
                            foreach ($typesBesoins as $type): 
                                if ($currentCategorie != $type['categorie']):
                                    if ($currentCategorie != '') echo '</optgroup>';
                                    $currentCategorie = $type['categorie'];
                                    echo '<optgroup label="' . ucfirst($type['categorie']) . '">';
                                endif;
                            ?>
                                <option value="<?= $type['id'] ?>" 
                                        data-unite="<?= htmlspecialchars($type['unite']) ?>"
                                        data-prix="<?= $type['prix_unitaire'] ?>">
                                    <?= htmlspecialchars($type['nom']) ?> (<?= htmlspecialchars($type['unite']) ?>) - 
                                    <?= number_format($type['prix_unitaire'], 0) ?> Ar
                                </option>
                            <?php endforeach; ?>
                            <?php if ($currentCategorie != '') echo '</optgroup>'; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantite" class="form-label">Quantité *</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" 
                               step="0.01" min="0.01" required>
                        <small class="form-text text-muted" id="uniteAffichage"></small>
                    </div>

                    <div class="mb-3" id="valeurEstimee" style="display: none;">
                        <div class="alert alert-info">
                            <strong>Valeur estimée:</strong> <span id="valeurCalculee">0</span> Ar
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="bi bi-save"></i> Enregistrer le besoin
                        </button>
                        <a href="/" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour au tableau de bord
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('type_besoin_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const unite = selectedOption.dataset.unite;
    
    if (unite) {
        document.getElementById('uniteAffichage').textContent = 'Unité: ' + unite;
    }
    
    calculerValeur();
});

document.getElementById('quantite').addEventListener('input', calculerValeur);

function calculerValeur() {
    const selectType = document.getElementById('type_besoin_id');
    const selectedOption = selectType.options[selectType.selectedIndex];
    const prix = parseFloat(selectedOption.dataset.prix) || 0;
    const quantite = parseFloat(document.getElementById('quantite').value) || 0;
    
    if (prix > 0 && quantite > 0) {
        const valeur = prix * quantite;
        document.getElementById('valeurCalculee').textContent = valeur.toLocaleString('fr-FR');
        document.getElementById('valeurEstimee').style.display = 'block';
    } else {
        document.getElementById('valeurEstimee').style.display = 'none';
    }
}

document.getElementById('formBesoin').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('/besoins', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Besoin enregistré avec succès !');
            window.location.href = '/';
        } else {
            alert('Erreur: ' + result.message);
        }
    })
    .catch(error => {
        alert('Erreur de connexion');
        console.error(error);
    });
});
</script>
