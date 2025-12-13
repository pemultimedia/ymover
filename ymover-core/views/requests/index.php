<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Richieste</h2>
    <a href="/requests/create" class="btn btn-primary">Nuova Richiesta</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Source</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="5" class="text-center">Nessuna richiesta trovata.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($requests as $req): ?>
                    <tr>
                        <td>#<?= htmlspecialchars((string)$req['id']) ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($req['status']) ?></span></td>
                        <td><?= htmlspecialchars($req['source']) ?></td>
                        <td><?= htmlspecialchars($req['created_at']) ?></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">Vedi</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
