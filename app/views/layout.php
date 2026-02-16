<?php
    $base = Flight::get('flight.base_url')
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'BNGRC - Gestion des Dons'; ?></title>
    <link href="<?= $base ?>/css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= $base ?>/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="bi bi-heart-fill"></i> BNGRC - Gestion des Dons
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="bi bi-speedometer2"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/besoins/create">
                            <i class="bi bi-exclamation-circle"></i> Saisir un besoin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dons/create">
                            <i class="bi bi-gift"></i> Enregistrer un don
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/distributions">
                            <i class="bi bi-truck"></i> Distributions
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <?php echo $content; ?>
    </div>

    <footer class="footer mt-5 py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">BNGRC - Bureau National de Gestion des Risques et Catastrophes © 2026</span>
        </div>
    </footer>

    <script src="<?= $base ?>/css/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $base ?>/css/bootstrap/js/app.js"></script>
</body>
</html>
