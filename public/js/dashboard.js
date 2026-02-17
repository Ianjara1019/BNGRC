function executerDispatch() {
    const mode = document.getElementById('dispatchMode').value;
    const modeText = mode === 'date' ? 'par défaut (par date)' : 'par demandes les plus petits';
    
    if (!confirm(`Voulez-vous vraiment exécuter le dispatch des dons en mode "${modeText}" ?`)) {
        return;
    }

    fetch('/distributions/dispatch', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'mode=' + encodeURIComponent(mode)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur de connexion');
        console.error(error);
    });
}

document.getElementById('confirmResetBtn').addEventListener('click', function() {
    // Désactiver le bouton pendant le traitement
    this.disabled = true;
    this.innerHTML = '<i class="bi bi-hourglass-split"></i> Réinitialisation...';

    fetch('/reset-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ Erreur: ' + data.message);
            // Réactiver le bouton en cas d'erreur
            document.getElementById('confirmResetBtn').disabled = false;
            document.getElementById('confirmResetBtn').innerHTML = '<i class="bi bi-arrow-counterclockwise"></i> Confirmer la réinitialisation';
        }
    })
    .catch(error => {
        alert('❌ Erreur de connexion lors de la réinitialisation');
        console.error(error);
        // Réactiver le bouton en cas d'erreur
        document.getElementById('confirmResetBtn').disabled = false;
        document.getElementById('confirmResetBtn').innerHTML = '<i class="bi bi-arrow-counterclockwise"></i> Confirmer la réinitialisation';
    });
});