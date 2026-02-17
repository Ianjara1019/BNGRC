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