<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4><i class="bi bi-truck"></i> Historique des distributions</h4>
            </div>
            <div class="card-body">
                <?php if (empty($distributions)) { ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucune distribution n'a encore été effectuée.
                        <br>Cliquez sur "Exécuter le dispatch" depuis le tableau de bord pour distribuer les dons.
                    </div>
                <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Ville</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Donateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($distributions as $dist) { ?>
                                <tr>
                                    <td><?= $dist['id'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($dist['date_distribution'])) ?></td>
                                    <td>
                                        <i class="bi bi-geo-alt-fill text-primary"></i>
                                        <?= htmlspecialchars($dist['ville_nom']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($dist['type_nom']) ?></td>
                                    <td>
                                        <strong><?= number_format($dist['quantite'], 2) ?></strong> 
                                        <?= htmlspecialchars($dist['unite']) ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-fill text-success"></i>
                                        <?= htmlspecialchars($dist['donateur'] ?? 'Anonyme') ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-success mt-3">
                        <strong>Total:</strong> <?= count($distributions) ?> distribution(s) effectuée(s)
                    </div>
                <?php } ?>

                <div class="mt-3">
                    <a href="/" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
