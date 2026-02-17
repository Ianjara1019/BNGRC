<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-warning text-white position-relative overflow-hidden">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">Déclarer un besoin</h4>
                        <small>Saisissez les informations du besoin humanitaire</small>
                    </div>
                </div>
                <!-- Element décoratif -->
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="bi bi-plus-circle fs-1"></i>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Alert d'information -->
                <div class="alert alert-info border-0 mb-4">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill text-info me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Informations importantes</h6>
                            <p class="mb-0 small">Tous les champs marqués d'un astérisque (*) sont obligatoires. Les prix unitaires sont affichés pour information.</p>
                        </div>
                    </div>
                </div>

                <form id="formBesoin">
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

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="/" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour au tableau de bord
                        </a>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="resetForm()">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle-fill me-2"></i>Enregistrer le besoin
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section d'aide -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="card-title text-muted">
                    <i class="bi bi-question-circle me-2"></i>Aide à la saisie
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="bi bi-egg-fried text-success fs-2 mb-2"></i>
                            <h6>Nature</h6>
                            <small class="text-muted">Aliments et produits de première nécessité</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="bi bi-tools text-primary fs-2 mb-2"></i>
                            <h6>Matériel</h6>
                            <small class="text-muted">Matériaux de construction et équipements</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="bi bi-cash text-warning fs-2 mb-2"></i>
                            <h6>Argent</h6>
                            <small class="text-muted">Fonds pour achats directs</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="/" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour au tableau de bord
                        </a>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="resetForm()">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle-fill me-2"></i>Enregistrer le besoin
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section d'aide -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="card-title text-muted">
                    <i class="bi bi-question-circle me-2"></i>Aide à la saisie
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="bi bi-egg-fried text-success fs-2 mb-2"></i>
                            <h6>Nature</h6>
                            <small class="text-muted">Aliments et produits de première nécessité</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="bi bi-tools text-primary fs-2 mb-2"></i>
                            <h6>Matériel</h6>
                            <small class="text-muted">Matériaux de construction et équipements</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="bi bi-cash text-warning fs-2 mb-2"></i>
                            <h6>Argent</h6>
                            <small class="text-muted">Fonds pour achats directs</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le formulaire
let selectedVille = null;
let selectedTypeBesoin = null;

// Animation d'entrée des éléments
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Gestionnaire pour la sélection de ville
document.getElementById('ville_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    selectedVille = {
        id: this.value,
        nom: selectedOption.text.split(' (')[0],
        region: selectedOption.dataset.region
    };

    updateFormState();
    showToast('Ville sélectionnée: ' + selectedVille.nom, 'info');
});

// Gestionnaire pour la sélection du type de besoin
document.getElementById('type_besoin_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const unite = selectedOption.dataset.unite;
    const prix = parseFloat(selectedOption.dataset.prix) || 0;
    const categorie = selectedOption.dataset.categorie;

    if (unite) {
        selectedTypeBesoin = {
            id: this.value,
            nom: selectedOption.text.split(' (')[0],
            unite: unite,
            prix: prix,
            categorie: categorie
        };

        // Mise à jour de l'affichage de l'unité
        document.getElementById('uniteAffichage').innerHTML = `<strong>${unite}</strong>`;

        // Mise à jour de l'aide contextuelle
        updateHelpText(categorie);

        updateFormState();
        calculerResume();

        showToast('Type sélectionné: ' + selectedTypeBesoin.nom, 'success');
    }
});

// Gestionnaire pour la quantité
document.getElementById('quantite').addEventListener('input', function() {
    calculerResume();
    validateQuantity();
});

// Mise à jour de l'état du formulaire
function updateFormState() {
    const quantiteInput = document.getElementById('quantite');
    const submitBtn = document.querySelector('button[type="submit"]');

    if (selectedVille && selectedTypeBesoin) {
        quantiteInput.disabled = false;
        submitBtn.disabled = false;
        document.getElementById('resumeSection').style.display = 'block';
    } else {
        quantiteInput.disabled = true;
        submitBtn.disabled = true;
        document.getElementById('resumeSection').style.display = 'none';
    }
}

// Mise à jour du texte d'aide selon la catégorie
function updateHelpText(categorie) {
    const aideElement = document.getElementById('aideQuantite');
    let helpText = '';

    switch(categorie) {
        case 'nature':
            helpText = 'Exemples: 100 kg de riz, 50 litres d\'huile, etc.';
            break;
        case 'materiel':
            helpText = 'Exemples: 200 tôles, 100 planches de bois, etc.';
            break;
        case 'argent':
            helpText = 'Montant en Ariary pour achats directs';
            break;
        default:
            helpText = 'Saisissez la quantité totale requise';
    }

    aideElement.textContent = helpText;
}

// Calcul et affichage du résumé
function calculerResume() {
    const quantite = parseFloat(document.getElementById('quantite').value) || 0;

    if (selectedTypeBesoin && quantite > 0) {
        const prixUnitaire = selectedTypeBesoin.prix;
        const valeurTotale = prixUnitaire * quantite;

        document.getElementById('prixUnitaire').textContent = prixUnitaire.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('valeurTotale').textContent = valeurTotale.toLocaleString('fr-FR') + ' Ar';

        // Animation du calcul
        animateValue('valeurTotale', valeurTotale);
    }
}

// Animation des valeurs numériques
function animateValue(elementId, targetValue) {
    const element = document.getElementById(elementId);
    const startValue = parseFloat(element.textContent.replace(/[^\d]/g, '')) || 0;
    const duration = 500;
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        const currentValue = startValue + (targetValue - startValue) * progress;
        element.textContent = Math.round(currentValue).toLocaleString('fr-FR') + ' Ar';

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

// Validation de la quantité
function validateQuantity() {
    const quantiteInput = document.getElementById('quantite');
    const quantite = parseFloat(quantiteInput.value);
    const submitBtn = document.querySelector('button[type="submit"]');

    if (quantite <= 0) {
        quantiteInput.classList.add('is-invalid');
        submitBtn.disabled = true;
    } else {
        quantiteInput.classList.remove('is-invalid');
        if (selectedVille && selectedTypeBesoin) {
            submitBtn.disabled = false;
        }
    }
}

// Réinitialisation du formulaire
function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les données saisies seront perdues.')) {
        document.getElementById('formBesoin').reset();
        selectedVille = null;
        selectedTypeBesoin = null;
        updateFormState();
        document.getElementById('resumeSection').style.display = 'none';
        showToast('Formulaire réinitialisé', 'warning');
    }
}

// Soumission du formulaire
document.getElementById('formBesoin').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!selectedVille || !selectedTypeBesoin) {
        showToast('Veuillez sélectionner une ville et un type de besoin', 'danger');
        return;
    }

    const quantite = parseFloat(document.getElementById('quantite').value);
    if (quantite <= 0) {
        showToast('Veuillez saisir une quantité valide', 'danger');
        return;
    }

    // Désactiver le bouton et afficher le loading
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Traitement...';

    const formData = new FormData(this);

    fetch('/besoins', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showToast('Besoin enregistré avec succès !', 'success');
            setTimeout(() => {
                window.location.href = '/';
            }, 1500);
        } else {
            showToast('Erreur: ' + result.message, 'danger');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur de connexion au serveur', 'danger');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Fonction d'affichage des toasts
function showToast(message, type = 'info') {
    // Créer l'élément toast
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'danger' ? 'x-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    // Ajouter au conteneur de toasts
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }

    toastContainer.appendChild(toast);

    // Initialiser et afficher le toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    // Supprimer le toast après qu'il soit caché
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updateFormState();
});
</script>
