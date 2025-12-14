<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= __('requests') ?></h2>
    <a href="/requests/create" class="btn btn-primary"><?= __('new_request') ?></a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-3">
            <input type="text" class="form-control" placeholder="<?= __('search') ?>">
        </div>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th><?= __('status') ?></th>
                    <th>Cliente</th>
                    <th><?= __('source') ?></th>
                    <th><?= __('created_at') ?></th>
                    <th><?= __('actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Nessuna richiesta trovata.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><span class="fw-bold">#<?= htmlspecialchars((string)$req['id']) ?></span></td>
                        <td>
                            <?php
                                $badgeClass = match($req['status']) {
                                    'new' => 'bg-primary',
                                    'contacted' => 'bg-info',
                                    'quoted' => 'bg-warning text-dark',
                                    'confirmed' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($req['status'])) ?></span>
                        </td>
                        <td><?= htmlspecialchars($req['customer_name']) ?></td>
                        <td><?= htmlspecialchars($req['source']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($req['created_at'])) ?></td>
                        <td>
                            <a href="/requests/show?id=<?= $req['id'] ?>" class="btn btn-sm btn-outline-primary"><?= __('view') ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
