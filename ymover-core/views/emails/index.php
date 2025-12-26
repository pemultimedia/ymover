<div class="mb-4">
    <h1>Comunicazioni</h1>
    <p class="text-muted">Log delle email inviate e ricevute dai clienti.</p>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Data</th>
                    <th>Mittente / Destinatario</th>
                    <th>Oggetto</th>
                    <th>Stato</th>
                    <th class="text-end">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($emails)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Nessun messaggio trovato.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($emails as $e): ?>
                        <tr>
                            <td><small><?= date('d/m/Y H:i', strtotime($e['received_at'])) ?></small></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($e['direction'] === 'inbound' ? $e['sender'] : $e['recipient']) ?></div>
                                <small class="text-muted"><?= $e['direction'] === 'inbound' ? 'Ricevuta' : 'Inviata' ?></small>
                            </td>
                            <td><?= htmlspecialchars($e['subject']) ?></td>
                            <td>
                                <span class="badge bg-<?= $e['direction'] === 'inbound' ? 'info' : 'secondary' ?>">
                                    <?= $e['direction'] ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary">Leggi</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
