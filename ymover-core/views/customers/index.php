<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Clienti</h1>
    <a href="/customers/create" class="btn btn-primary">Nuovo Cliente</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Codice Fiscale / P.IVA</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Nessun cliente trovato.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($customer['name']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge <?= $customer['type'] === 'company' ? 'bg-info' : 'bg-secondary' ?>">
                                        <?= $customer['type'] === 'company' ? 'Azienda' : 'Privato' ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($customer['tax_code'] ?? '-') ?></td>
                                <td>
                                    <a href="/customers/show?id=<?= $customer['id'] ?>" class="btn btn-sm btn-outline-primary">Visualizza</a>
                                    <a href="/customers/edit?id=<?= $customer['id'] ?>" class="btn btn-sm btn-outline-secondary">Modifica</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
