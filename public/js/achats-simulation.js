let selectedBesoin = null;

document.querySelectorAll('.acheter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        selectedBesoin = {
            id: this.dataset.besoinId,
            type: this.dataset.type,
            quantiteMax: parseFloat(this.dataset.quantiteMax),
            prix: parseFloat(this.dataset.prix)
        };

        document.getElementById('besoinId').value = selectedBesoin.id;
        document.getElementById('typeBesoin').value = selectedBesoin.type;
        document.getElementById('quantiteMax').value = selectedBesoin.quantiteMax;
        document.getElementById('prixUnitaire').textContent = selectedBesoin.prix.toLocaleString();
        document.getElementById('quantite').max = selectedBesoin.quantiteMax;

        new bootstrap.Modal(document.getElementById('achatModal')).show();
    });
});

document.getElementById('quantite').addEventListener('input', calculerTotal);
document.getElementById('fraisPercent').addEventListener('input', calculerTotal);

function calculerTotal() {
    const quantite = parseFloat(document.getElementById('quantite').value) || 0;
    const fraisPercent = parseFloat(document.getElementById('fraisPercent').value) || 0;
    const prix = selectedBesoin ? selectedBesoin.prix : 0;

    const valeurAchat = quantite * prix;
    const valeurTotale = valeurAchat * (1 + fraisPercent / 100);

    document.getElementById('valeurAchat').textContent = valeurAchat.toLocaleString();
    document.getElementById('valeurTotale').textContent = valeurTotale.toLocaleString();
}

document.getElementById('simulerBtn').addEventListener('click', function() {
    const formData = new FormData(document.getElementById('achatForm'));

    fetch('/achats', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            bootstrap.Modal.getInstance(document.getElementById('achatModal')).hide();
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la simulation');
    });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('valider-btn')) {
        const achatId = e.target.dataset.achatId;

        if (confirm('Êtes-vous sûr de vouloir valider cet achat ?')) {
            fetch('/achats/valider', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'achat_id=' + achatId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            });
        }
    }

    if (e.target.classList.contains('supprimer-btn')) {
        const achatId = e.target.dataset.achatId;

        if (confirm('Êtes-vous sûr de vouloir supprimer cet achat de la simulation ?')) {
            fetch('/achats/simulation', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'achat_id=' + achatId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            });
        }
    }
});