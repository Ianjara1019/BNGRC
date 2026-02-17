<h1><?php echo $title; ?></h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Besoins restants</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Type</th>
                                <th>Quantité manquante</th>
                                <th>Prix unitaire</th>
                                <th>Valeur totale</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($besoins as $besoin): ?>
                                <?php if ($besoin['categorie'] !== 'argent' && $besoin['quantite_manquante'] > 0): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($besoin['ville_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($besoin['type_nom']); ?> (<?php echo htmlspecialchars($besoin['unite']); ?>)</td>
                                        <td><?php echo number_format($besoin['quantite_manquante'], 2); ?></td>
                                        <td><?php echo number_format($besoin['prix_unitaire'], 0); ?> Ar</td>
                                        <td><?php echo number_format($besoin['quantite_manquante'] * $besoin['prix_unitaire'], 0); ?> Ar</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary acheter-btn"
                                                    data-besoin-id="<?php echo $besoin['id']; ?>"
                                                    data-type="<?php echo htmlspecialchars($besoin['type_nom']); ?>"
                                                    data-quantite-max="<?php echo $besoin['quantite_manquante']; ?>"
                                                    data-prix="<?php echo $besoin['prix_unitaire']; ?>">
                                                Acheter
                                            </button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Fonds disponibles</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6>Total des dons en argent disponibles:</h6>
                    <h4 class="text-success"><?php echo number_format($totalFondsDisponibles, 0); ?> Ar</h4>
                    <small class="text-muted">Répartis sur <?php echo count($donsArgent); ?> don(s)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'achat -->
<div class="modal fade" id="achatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Effectuer un achat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="achatForm">
                    <input type="hidden" id="besoinId" name="besoin_id">

                    <div class="mb-3">
                        <label class="form-label">Type de besoin:</label>
                        <input type="text" class="form-control" id="typeBesoin" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantité maximale:</label>
                        <input type="text" class="form-control" id="quantiteMax" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="quantite" class="form-label">Quantité à acheter:</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" step="0.01" min="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="fraisPercent" class="form-label">Frais d'achat (%):</label>
                        <input type="number" class="form-control" id="fraisPercent" name="frais_percent" step="0.01" min="0" value="10" required>
                    </div>

                    <div class="mb-3">
                        <strong>Prix unitaire: <span id="prixUnitaire">0</span> Ar</strong><br>
                        <strong>Valeur d'achat: <span id="valeurAchat">0</span> Ar</strong><br>
                        <strong>Valeur totale avec frais: <span id="valeurTotale">0</span> Ar</strong>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="simulerBtn">Simuler</button>
            </div>
        </div>
    </div>
</div>

<!-- Achats en simulation -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Achats en simulation</h5>
    </div>
    <div class="card-body">
        <div id="simulationList">
            <?php if (empty($achatsSimulation)): ?>
                <p class="text-muted">Aucun achat en simulation</p>
            <?php else: ?>
                <?php foreach ($achatsSimulation as $achat): ?>
                    <div class="alert alert-warning simulation-item" data-achat-id="<?php echo $achat['id']; ?>">
                        <strong><?php echo htmlspecialchars($achat['ville_nom']); ?> - <?php echo htmlspecialchars($achat['type_nom']); ?></strong><br>
                        Quantité: <?php echo number_format($achat['quantite'], 2); ?> | Valeur: <?php echo number_format($achat['valeur_totale_avec_frais'], 0); ?> Ar
                        <div class="float-end">
                            <button class="btn btn-sm btn-success valider-btn" data-achat-id="<?php echo $achat['id']; ?>">Valider</button>
                            <button class="btn btn-sm btn-danger supprimer-btn" data-achat-id="<?php echo $achat['id']; ?>">Supprimer</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
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
</script>