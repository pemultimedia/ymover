<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= __('new_request') ?></h2>
    <a href="/requests" class="btn btn-outline-secondary"><?= __('back') ?></a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Dati Iniziali</h5>
            </div>
            <div class="card-body">
                <form action="/requests/store" method="POST">
                    <!-- Customer Section -->
                    <h6 class="text-muted mb-3">Cliente</h6>
                    <div class="mb-3">
                        <label class="form-label">Seleziona Cliente Esistente</label>
                        <select name="customer_id" class="form-select mb-2">
                            <option value="">-- Nuovo Cliente --</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-center text-muted my-2 small">oppure crea nuovo</div>
                        <input type="text" name="customer_name" class="form-control" placeholder="Nome Nuovo Cliente / Ragione Sociale">
                    </div>

                    <hr class="my-4">

                    <!-- Logistics Section -->
                    <h6 class="text-muted mb-3">Logistica (Indirizzi Principali)</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Origine (Carico)</label>
                            <input type="text" name="origin_address" class="form-control" placeholder="Via, Città">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destinazione (Scarico)</label>
                            <input type="text" name="destination_address" class="form-control" placeholder="Via, Città">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Request Details -->
                    <h6 class="text-muted mb-3">Dettagli Richiesta</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= __('source') ?></label>
                            <select name="source" class="form-select">
                                <option value="manual">Manuale (Telefono/Email)</option>
                                <option value="web">Sito Web</option>
                                <option value="referral">Passaparola</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Prevista (Opzionale)</label>
                            <input type="date" name="expected_date" class="form-control">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Note Interne</label>
                        <textarea name="internal_notes" class="form-control" rows="3" placeholder="Note iniziali per l'operativo..."></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-success px-4"><?= __('save') ?> e Continua</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
