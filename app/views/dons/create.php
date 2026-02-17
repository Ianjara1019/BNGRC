<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-success text-white position-relative overflow-hidden">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2 me-3">
                        <i class="bi bi-heart-fill fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">Enregistrer un don</h4>
                        <small>Contribuez à aider les populations affectées</small>
                    </div>
                </div>
                <!-- Element décoratif -->
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="bi bi-gift-fill fs-1"></i>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Alert de remerciement -->
                <div class="alert alert-success border-0 mb-4">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-heart-fill text-success me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Merci pour votre générosité !</h6>
                            <p class="mb-0 small">Votre don contribuera à aider les populations dans le besoin. Tous les champs marqués d'un astérisque (*) sont obligatoires.</p>
                        </div>
                    </div>
                </div>

                <form id="formDon">
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
                                <i class="bi bi-heart-fill me-2"></i>Enregistrer le don
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section d'information -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="card-title text-muted">
                    <i class="bi bi-info-circle me-2"></i>Comment ça marche ?
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-check-circle-fill text-success fs-3"></i>
                            </div>
                            <h6 class="mt-2">1. Choisir</h6>
                            <small class="text-muted">Sélectionnez le type et la quantité de votre don</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-truck text-primary fs-3"></i>
                            </div>
                            <h6 class="mt-2">2. Distribuer</h6>
                            <small class="text-muted">Votre don est automatiquement distribué aux besoins</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-graph-up text-warning fs-3"></i>
                            </div>
                            <h6 class="mt-2">3. Impact</h6>
                            <small class="text-muted">Suivez l'impact de votre contribution</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le formulaire
let selectedTypeDon = null;

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

// Gestionnaire pour la sélection du type de don
document.getElementById('type_besoin_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const unite = selectedOption.dataset.unite;
    const prix = parseFloat(selectedOption.dataset.prix) || 0;
    const categorie = selectedOption.dataset.categorie;

    if (unite) {
        selectedTypeDon = {
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

        showToast('Type de don sélectionné: ' + selectedTypeDon.nom, 'success');
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

    if (selectedTypeDon) {
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
            helpText = 'Exemples: 25 kg de riz, 10 litres d\'huile, etc.';
            break;
        case 'materiel':
            helpText = 'Exemples: 50 tôles, 20 planches de bois, etc.';
            break;
        case 'argent':
            helpText = 'Montant en Ariary pour aider les achats directs';
            break;
        default:
            helpText = 'Saisissez la quantité que vous souhaitez donner';
    }

    aideElement.textContent = helpText;
}

// Calcul et affichage du résumé
function calculerResume() {
    const quantite = parseFloat(document.getElementById('quantite').value) || 0;

    if (selectedTypeDon && quantite > 0) {
        const prixUnitaire = selectedTypeDon.prix;
        const valeurTotale = prixUnitaire * quantite;

        document.getElementById('prixUnitaire').textContent = prixUnitaire.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('valeurTotale').textContent = valeurTotale.toLocaleString('fr-FR') + ' Ar';

        // Animation du calcul
        animateValue('valeurTotale', valeurTotale);

        // Message d'impact
        updateImpactMessage(selectedTypeDon.categorie, quantite);
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

// Mise à jour du message d'impact
function updateImpactMessage(categorie, quantite) {
    const impactElement = document.getElementById('impactMessage');
    let message = '';

    switch(categorie) {
        case 'nature':
            if (quantite >= 50) {
                message = 'Votre don peut nourrir une famille pendant plusieurs semaines !';
            } else {
                message = 'Votre don contribue à améliorer l\'alimentation des populations affectées';
            }
            break;
        case 'materiel':
            message = 'Votre don matériel va aider à reconstruire des abris et infrastructures';
            break;
        case 'argent':
            message = 'Votre don financier permet des achats directs selon les besoins prioritaires';
            break;
        default:
            message = 'Votre générosité va aider de nombreuses personnes dans le besoin';
    }

    impactElement.textContent = message;
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
        if (selectedTypeDon) {
            submitBtn.disabled = false;
        }
    }
}

// Réinitialisation du formulaire
function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les données saisies seront perdues.')) {
        document.getElementById('formDon').reset();
        selectedTypeDon = null;
        updateFormState();
        document.getElementById('resumeSection').style.display = 'none';
        showToast('Formulaire réinitialisé', 'warning');
    }
}

// Soumission du formulaire
document.getElementById('formDon').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!selectedTypeDon) {
        showToast('Veuillez sélectionner un type de don', 'danger');
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

    fetch('/dons', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showToast('Don enregistré avec succès ! Merci pour votre générosité.', 'success');
            setTimeout(() => {
                window.location.href = '/';
            }, 2000);
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
