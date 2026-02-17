<h1><?php echo $title; ?></h1>

<div class="row mb-4">
    <div class="col-md-4">
        <label for="villeFilter" class="form-label">Filtrer par ville:</label>
        <select id="villeFilter" class="form-select">
            <option value="">Toutes les villes</option>
            <?php foreach ($villes as $ville): ?>
                <option value="<?php echo $ville['id']; ?>" <?php echo $villeFiltre == $ville['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($ville['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Ville</th>
                <th>Type de besoin</th>
                <th>Quantité</th>
                <th>Valeur d'achat</th>
                <th>Frais (%)</th>
                <th>Valeur totale</th>
                <th>Source</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($achats)): ?>
                <tr>
                    <td colspan="8" class="text-center">Aucun achat trouvé</td>
                </tr>
            <?php else: ?>
                <?php foreach ($achats as $achat): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($achat['date_achat'])); ?></td>
                        <td><?php echo htmlspecialchars($achat['ville_nom']); ?></td>
                        <td><?php echo htmlspecialchars($achat['type_nom']); ?> (<?php echo htmlspecialchars($achat['unite']); ?>)</td>
                        <td><?php echo number_format($achat['quantite'], 2); ?></td>
                        <td><?php echo number_format($achat['valeur_achat'], 0); ?> Ar</td>
                        <td><?php echo number_format($achat['frais_percent'], 2); ?>%</td>
                        <td><?php echo number_format($achat['valeur_totale_avec_frais'], 0); ?> Ar</td>
                        <td><?php echo htmlspecialchars($achat['source_don']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('villeFilter').addEventListener('change', function() {
    const villeId = this.value;
    const url = villeId ? `?ville_id=${villeId}` : '';
    window.location.href = window.location.pathname + url;
});
</script>