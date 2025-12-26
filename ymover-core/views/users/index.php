<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestione Team</h1>
    <a href="/users/create" class="btn btn-primary">+ Nuovo Membro</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ruolo</th>
                    <th class="text-end">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Nessun membro del team trovato.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><div class="fw-bold"><?= htmlspecialchars($u['name']) ?></div></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="/users/edit?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary">Modifica</a>
                                <a href="/users/delete?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Sicuro di voler eliminare questo utente?')">Elimina</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
