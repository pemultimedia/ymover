<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestione Risorse</h1>
    <a href="/resources/create" class="btn btn-primary">+ Nuova Risorsa</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Costo/h</th>
                    <th>Costo/km</th>
                    <th>Stato</th>
                    <th class="text-end">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($resources)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Nessuna risorsa trovata.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($resources as $r): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($r['name']) ?></div>
                                <?php 
                                    $specs = json_decode($r['specs'] ?? '{}', true);
                                    if ($r['type'] === 'vehicle' && isset($specs['plate'])): 
                                ?>
                                    <small class="text-muted">Targa: <?= htmlspecialchars($specs['plate']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <?= $r['type'] === 'vehicle' ? 'Veicolo' : ($r['type'] === 'employee' ? 'Personale' : 'Attrezzatura') ?>
                                </span>
                            </td>
                            <td>€ <?= number_format((float)$r['cost_per_hour'], 2, ',', '.') ?></td>
                            <td>€ <?= number_format((float)$r['cost_per_km'], 2, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-<?= $r['is_active'] ? 'success' : 'danger' ?>">
                                    <?= $r['is_active'] ? 'Attiva' : 'Inattiva' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="/resources/edit?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">Modifica</a>
                                <a href="/resources/delete?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Sicuro di voler eliminare questa risorsa?')">Elimina</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
