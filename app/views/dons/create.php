<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-success text-white position-relative overflow-hidden">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2 me-3">
                        <i class="bi bi-heart-fill fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">Enregistrer un don</h4>
                        <small>Contribuez à aider les populations affectées</small>
                    </div>
                </div>
                <!-- Element décoratif -->
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="bi bi-gift-fill fs-1"></i>
                </div>
            </div>

            <div class="card-body p-4">
                <?php include 'partials/alert_success.php'; ?>

                <form id="formDon">
                    <?php include 'partials/form_fields.php'; ?>
                    <?php include 'partials/form_actions.php'; ?>
                </form>
            </div>
        </div>

        <?php include 'partials/info_section.php'; ?>
    </div>
</div>

<script src="/js/dons-create.js"></script>
