<h1><?php echo $title; ?></h1>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Récapitulatif des besoins</h5>
                <button id="refreshBtn" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Actualiser
                </button>
            </div>
            <div class="card-body">
                <div id="recapContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadRecapitulatif() {
    fetch('/api/recapitulatif')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const recap = data.data;
                document.getElementById('recapContent').innerHTML = `
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3>${parseFloat(recap.besoins_totaux || 0).toLocaleString()} Ar</h3>
                                    <p>Besoins totaux</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3>${parseFloat(recap.besoins_satisfaits || 0).toLocaleString()} Ar</h3>
                                    <p>Besoins satisfaits</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h3>${parseFloat(recap.besoins_restants || 0).toLocaleString()} Ar</h3>
                                    <p>Besoins restants</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: ${recap.besoins_totaux > 0 ? (recap.besoins_satisfaits / recap.besoins_totaux * 100) : 0}%"
                                 aria-valuenow="${recap.besoins_totaux > 0 ? (recap.besoins_satisfaits / recap.besoins_totaux * 100) : 0}"
                                 aria-valuemin="0" aria-valuemax="100">
                                Satisfaits: ${recap.besoins_totaux > 0 ? Math.round(recap.besoins_satisfaits / recap.besoins_totaux * 100) : 0}%
                            </div>
                            <div class="progress-bar bg-warning" role="progressbar"
                                 style="width: ${recap.besoins_totaux > 0 ? (recap.besoins_restants / recap.besoins_totaux * 100) : 0}%"
                                 aria-valuenow="${recap.besoins_totaux > 0 ? (recap.besoins_restants / recap.besoins_totaux * 100) : 0}"
                                 aria-valuemin="0" aria-valuemax="100">
                                Restants: ${recap.besoins_totaux > 0 ? Math.round(recap.besoins_restants / recap.besoins_totaux * 100) : 0}%
                            </div>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('recapContent').innerHTML = '<div class="alert alert-danger">Erreur lors du chargement des données</div>';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('recapContent').innerHTML = '<div class="alert alert-danger">Erreur de connexion</div>';
        });
}

document.getElementById('refreshBtn').addEventListener('click', function() {
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualisation...';
    this.disabled = true;

    loadRecapitulatif();

    setTimeout(() => {
        this.innerHTML = '<i class="fas fa-sync-alt"></i> Actualiser';
        this.disabled = false;
    }, 1000);
});

// Charger les données au chargement de la page
loadRecapitulatif();
</script>