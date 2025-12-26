<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/customers">Clienti</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($customer['name']) ?></li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h1><?= htmlspecialchars($customer['name']) ?></h1>
        <a href="/customers/edit?id=<?= $customer['id'] ?>" class="btn btn-outline-secondary">Modifica</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Dettagli Cliente</h5>
            </div>
            <div class="card-body">
                <p class="mb-1 text-muted small">Tipo</p>
                <p class="mb-3">
                    <span class="badge <?= $customer['type'] === 'company' ? 'bg-info' : 'bg-secondary' ?>">
                        <?= $customer['type'] === 'company' ? 'Azienda' : 'Privato' ?>
                    </span>
                </p>

                <p class="mb-1 text-muted small">Codice Fiscale / P.IVA</p>
                <p class="mb-3"><?= htmlspecialchars($customer['tax_code'] ?? '-') ?></p>

                <p class="mb-1 text-muted small">Note</p>
                <p class="mb-0"><?= nl2br(htmlspecialchars($customer['notes'] ?? 'Nessuna nota.')) ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Storico Richieste</h5>
                <a href="/requests/create?customer_id=<?= $customer['id'] ?>" class="btn btn-sm btn-primary">Nuova Richiesta</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Stato</th>
                                <th>Data</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($requests)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nessuna richiesta trovata per questo cliente.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($requests as $req): ?>
                                    <tr>
                                        <td>#<?= $req['id'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $req['status'] === 'new' ? 'primary' : ($req['status'] === 'confirmed' ? 'success' : 'secondary') ?>">
                                                <?= ucfirst($req['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($req['created_at'])) ?></td>
                                        <td>
                                            <a href="/requests/show?id=<?= $req['id'] ?>" class="btn btn-sm btn-outline-primary">Visualizza</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
