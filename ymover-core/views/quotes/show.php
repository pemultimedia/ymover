<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/requests">Richieste</a></li>
            <li class="breadcrumb-item"><a href="/requests/show?id=<?= $request['id'] ?>">Richiesta #<?= $request['id'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Preventivo #<?= $quote['id'] ?></li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h1>Preventivo #<?= $quote['id'] ?></h1>
        <div>
            <span class="badge bg-<?= $quote['status'] === 'accepted' ? 'success' : ($quote['status'] === 'rejected' ? 'danger' : 'secondary') ?> fs-6">
                <?= ucfirst($quote['status']) ?>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Dettagli Economici</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Importo Totale:</div>
                    <div class="col-sm-8 fw-bold fs-5">€ <?= number_format((float)$quote['total_amount'], 2, ',', '.') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Data Creazione:</div>
                    <div class="col-sm-8"><?= date('d/m/Y H:i', strtotime($quote['created_at'])) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Scadenza:</div>
                    <div class="col-sm-8"><?= $quote['expiration_date'] ? date('d/m/Y', strtotime($quote['expiration_date'])) : 'Nessuna' ?></div>
                </div>
                <?php if ($quote['internal_notes']): ?>
                <div class="row">
                    <div class="col-sm-4 text-muted">Note Interne:</div>
                    <div class="col-sm-8 small"><?= nl2br(htmlspecialchars($quote['internal_notes'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Link Pubblico</h5>
            </div>
            <div class="card-body">
                <p class="small text-muted">Invia questo link al cliente per fargli visionare e accettare il preventivo online.</p>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]/quotes/public?id=" . $quote['id'] ?>" readonly id="publicLink">
                    <button class="btn btn-outline-primary" type="button" onclick="copyLink()">Copia</button>
                    <a href="/quotes/public?id=<?= $quote['id'] ?>" target="_blank" class="btn btn-primary">Apri</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyLink() {
    var copyText = document.getElementById("publicLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    alert("Link copiato negli appunti!");
}
</script>
