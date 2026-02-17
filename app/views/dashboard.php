<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-1"><i class="bi bi-speedometer2 text-primary"></i> Tableau de bord BNGRC</h1>
                <p class="text-muted mb-0">Gestion des dons et besoins - Bureau National de Gestion des Risques et Catastrophes</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="actualiserStats()">
                    <i class="bi bi-arrow-clockwise"></i> Actualiser
                </button>
                <button class="btn btn-success" onclick="executerDispatch()">
                    <i class="bi bi-play-circle-fill"></i> Exécuter le dispatch
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alertes importantes -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
                <div>
                    <h6 class="alert-heading mb-1">Bienvenue dans le système de gestion BNGRC</h6>
                    <p class="mb-0">Dernière mise à jour: <span id="lastUpdate"><?php echo date('d/m/Y H:i'); ?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bouton de réinitialisation -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-warning border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Réinitialisation des données</h6>
                        <p class="mb-0">Remettre la base de données à son état initial avec les données de test</p>
                    </div>
                </div>
                <button type="button" class="btn btn-warning" id="resetDataBtn" data-bs-toggle="modal" data-bs-target="#resetModal">
                    <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques principales -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-geo-alt-fill text-primary fs-2"></i>
                    </div>
                    <div class="text-start">
                        <h2 class="mb-0 text-primary fw-bold"><?= $stats['total_villes'] ?></h2>
                        <small class="text-muted">Villes couvertes</small>
                    </div>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-2"></i>
                    </div>
                    <div class="text-start">
                        <h2 class="mb-0 text-warning fw-bold"><?= $stats['total_besoins'] ?></h2>
                        <small class="text-muted">Besoins déclarés</small>
                    </div>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="bi bi-heart-fill text-success fs-2"></i>
                    </div>
                    <div class="text-start">
                        <h2 class="mb-0 text-success fw-bold"><?= $stats['total_dons'] ?></h2>
                        <small class="text-muted">Dons reçus</small>
                    </div>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 90%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="bi bi-truck text-info fs-2"></i>
                    </div>
                    <div class="text-start">
                        <h2 class="mb-0 text-info fw-bold"><?= $stats['total_distributions'] ?></h2>
                        <small class="text-muted">Distributions</small>
                    </div>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-info" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php foreach ($tableauBord as $item) { ?>
<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-gradient-primary text-white position-relative overflow-hidden">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">
                    <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($item['ville']['nom']) ?>
                </h4>
                <small class="text-white-50">
                    <i class="bi bi-geo"></i> <?= htmlspecialchars($item['ville']['region']) ?>
                </small>
            </div>
            <div class="text-end">
                <div class="d-flex gap-2">
                    <div class="text-center">
                        <div class="fs-5 fw-bold"><?php echo count($item['besoins']); ?></div>
                        <small class="text-white-75">Besoins</small>
                    </div>
                    <div class="text-center">
                        <div class="fs-5 fw-bold"><?php echo count($item['distributions']); ?></div>
                        <small class="text-white-75">Distributions</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- Element décoratif -->
        <div class="position-absolute top-0 end-0 opacity-25">
            <i class="bi bi-building fs-1"></i>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-2"></i>
                    <h5 class="mb-0 text-warning">Besoins déclarés</h5>
                </div>

                <?php if (empty($item['besoins'])) { ?>
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle-fill text-success fs-1 mb-2"></i>
                        <p class="text-muted mb-0">Aucun besoin en attente</p>
                        <small class="text-muted">Tous les besoins sont satisfaits</small>
                    </div>
                <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-tag"></i> Type</th>
                                    <th><i class="bi bi-circle-square"></i> Catégorie</th>
                                    <th><i class="bi bi-dash-circle"></i> Manquant</th>
                                    <th><i class="bi bi-flag"></i> Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($item['besoins'] as $besoin) {
                                    $pourcentage = $besoin['quantite'] > 0 ? ($besoin['quantite_recue'] / $besoin['quantite']) * 100 : 0;
                                    $statutClass = $besoin['statut'] === 'satisfait' ? 'success' : ($besoin['statut'] === 'partiel' ? 'warning' : 'danger');
                                    $statutIcon = $besoin['statut'] === 'satisfait' ? 'check-circle' : ($besoin['statut'] === 'partiel' ? 'dash-circle' : 'x-circle');
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <?php if ($besoin['categorie'] === 'nature'): ?>
                                                    <i class="bi bi-egg-fried text-success"></i>
                                                <?php elseif ($besoin['categorie'] === 'materiel'): ?>
                                                    <i class="bi bi-tools text-primary"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-cash text-warning"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="fw-medium"><?= htmlspecialchars($besoin['type_nom']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($besoin['unite']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $besoin['categorie'] === 'nature' ? 'success' : ($besoin['categorie'] === 'materiel' ? 'primary' : 'warning'); ?> bg-opacity-10 text-<?php echo $besoin['categorie'] === 'nature' ? 'success' : ($besoin['categorie'] === 'materiel' ? 'primary' : 'warning'); ?>">
                                            <?= ucfirst(htmlspecialchars($besoin['categorie'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-danger">
                                                <?= number_format($besoin['quantite_manquante'], 2) ?> <?= htmlspecialchars($besoin['unite']) ?>
                                            </span>
                                            <div class="progress mt-1" style="height: 4px;">
                                                <div class="progress-bar bg-<?= $statutClass ?>" style="width: <?= $pourcentage ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $statutClass ?> bg-opacity-10 text-<?= $statutClass ?>">
                                            <i class="bi bi-<?= $statutIcon ?>"></i>
                                            <?php if ($besoin['statut'] === 'satisfait'): ?>
                                                Satisfait
                                            <?php elseif ($besoin['statut'] === 'partiel'): ?>
                                                Partiel (<?= round($pourcentage) ?>%)
                                            <?php else: ?>
                                                En attente
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>

            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-truck text-info fs-4 me-2"></i>
                    <h5 class="mb-0 text-info">Dernières distributions</h5>
                </div>

                <?php if (empty($item['distributions'])) { ?>
                    <div class="text-center py-4">
                        <i class="bi bi-truck text-muted fs-1 mb-2"></i>
                        <p class="text-muted mb-0">Aucune distribution</p>
                        <small class="text-muted">Les distributions apparaîtront ici</small>
                    </div>
                <?php } else { ?>
                    <div class="timeline">
                        <?php
                        $recentDistributions = array_slice($item['distributions'], 0, 5);
                        foreach ($recentDistributions as $distribution) {
                        ?>
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-medium">
                                            <i class="bi bi-box-seam"></i>
                                            <?= htmlspecialchars($distribution['type_nom']) ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= number_format($distribution['quantite'], 2) ?> <?= htmlspecialchars($distribution['unite']) ?>
                                            • Donateur: <?= htmlspecialchars($distribution['donateur']) ?>
                                        </small>
                                    </div>
                                    <small class="text-muted">
                                        <?= date('d/m', strtotime($distribution['date_distribution'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
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

<!-- Modal de réinitialisation -->
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    Confirmation de réinitialisation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h6><i class="bi bi-exclamation-triangle-fill me-2"></i>Attention !</h6>
                    <p class="mb-0">Cette action va :</p>
                    <ul class="mb-0 mt-2">
                        <li>Supprimer toutes les données actuelles</li>
                        <li>Remettre la base de données à son état initial</li>
                        <li>Recharger les données de test</li>
                    </ul>
                    <p class="mt-2 mb-0"><strong>Cette action est irréversible !</strong></p>
                </div>
                <p>Êtes-vous sûr de vouloir continuer ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Annuler
                </button>
                <button type="button" class="btn btn-warning" id="confirmResetBtn">
                    <i class="bi bi-arrow-counterclockwise"></i> Confirmer la réinitialisation
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>
