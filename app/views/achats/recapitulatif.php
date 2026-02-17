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

<script src="/js/achats-recapitulatif.js"></script>