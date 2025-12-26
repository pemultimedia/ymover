<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/requests">Richieste</a></li>
            <li class="breadcrumb-item"><a href="/requests/show?id=<?= $request['id'] ?>">Richiesta #<?= $request['id'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuovo Preventivo</li>
        </ol>
    </nav>
    <h1>Crea Preventivo</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="/quotes/store" method="POST">
                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Versione Inventario di Riferimento</label>
                        <select name="inventory_version_id" class="form-select" required>
                            <?php foreach ($versions as $v): ?>
                                <option value="<?= $v['id'] ?>" <?= $v['is_selected'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Importo Totale (€)</label>
                            <input type="number" step="0.01" name="total_amount" class="form-control" required placeholder="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Scadenza</label>
                            <input type="date" name="expiration_date" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Note Interne (non visibili al cliente)</label>
                        <textarea name="internal_notes" class="form-control" rows="3"></textarea>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/requests/show?id=<?= $request['id'] ?>" class="btn btn-outline-secondary">Annulla</a>
                        <button type="submit" class="btn btn-success">Genera Preventivo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm bg-light">
            <div class="card-body">
                <h6>Suggerimento Calcolo</h6>
                <p class="small text-muted">Puoi basare il prezzo sul volume totale dell'inventario selezionato.</p>
                <hr>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="alert('Funzionalità di calcolo automatico in arrivo!')">Calcola Automaticamente</button>
                </div>
            </div>
        </div>
    </div>
</div>
