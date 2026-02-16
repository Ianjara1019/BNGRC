<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="bi bi-speedometer2"></i> Tableau de bord</h1>
            <button class="btn btn-success" onclick="executerDispatch()">
                <i class="bi bi-arrow-repeat"></i> Exécuter le dispatch
            </button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-geo-alt"></i> Villes</h5>
                <h2><?= $stats['total_villes'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-exclamation-circle"></i> Besoins</h5>
                <h2><?= $stats['total_besoins'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-gift"></i> Dons</h5>
                <h2><?= $stats['total_dons'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-truck"></i> Distributions</h5>
                <h2><?= $stats['total_distributions'] ?></h2>
            </div>
        </div>
    </div>
</div>

<?php foreach ($tableauBord as $item) { ?>
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($item['ville']['nom']) ?> 
            <small class="text-white-50">(<?= htmlspecialchars($item['ville']['region']) ?>)</small>
        </h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-warning"><i class="bi bi-exclamation-triangle"></i> Besoins</h5>
                <?php if (empty($item['besoins'])) { ?>
                    <p class="text-muted">Aucun besoin enregistré</p>
                <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Catégorie</th>
                                    <th>Quantité</th>
                                    <th>Reçu</th>
                                    <th>Manquant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($item['besoins'] as $besoin) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($besoin['type_nom']) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($besoin['categorie']) ?></span></td>
                                    <td><?= number_format($besoin['quantite'], 2) ?> <?= htmlspecialchars($besoin['unite']) ?></td>
                                    <td><?= number_format($besoin['quantite_recue'], 2) ?> <?= htmlspecialchars($besoin['unite']) ?></td>
                                    <td class="text-danger fw-bold"><?= number_format($besoin['quantite_manquante'], 2) ?> <?= htmlspecialchars($besoin['unite']) ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = [
                                            'en_attente' => 'bg-danger',
                                            'partiel' => 'bg-warning',
                                            'satisfait' => 'bg-success'
                                        ][$besoin['statut']] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($besoin['statut']) ?></span>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
            
            <div class="col-md-6">
                <h5 class="text-success"><i class="bi bi-box-seam"></i> Distributions reçues</h5>
                <?php if (empty($item['distributions'])) { ?>
                    <p class="text-muted">Aucune distribution effectuée</p>
                <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Donateur</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($item['distributions'] as $dist) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($dist['type_nom']) ?></td>
                                    <td><?= number_format($dist['quantite'], 2) ?> <?= htmlspecialchars($dist['unite']) ?></td>
                                    <td><?= htmlspecialchars($dist['donateur'] ?? 'Anonyme') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($dist['date_distribution'])) ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script>
function executerDispatch() {
    if (!confirm('Voulez-vous vraiment exécuter le dispatch des dons ?')) {
        return;
    }
    
    fetch('/distributions/dispatch', {
        method: 'POST'
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
</script>
