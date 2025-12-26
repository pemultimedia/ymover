<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/resources">Risorse</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modifica Risorsa</li>
        </ol>
    </nav>
    <h1>Modifica Risorsa: <?= htmlspecialchars($resource['name']) ?></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="/resources/update" method="POST">
                    <input type="hidden" name="id" value="<?= $resource['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Risorsa</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($resource['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo Risorsa</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="vehicle" <?= $resource['type'] === 'vehicle' ? 'selected' : '' ?>>Veicolo</option>
                            <option value="employee" <?= $resource['type'] === 'employee' ? 'selected' : '' ?>>Personale (Esterno/Collaboratore)</option>
                            <option value="equipment" <?= $resource['type'] === 'equipment' ? 'selected' : '' ?>>Attrezzatura (Scala, Transpallet, etc.)</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cost_per_hour" class="form-label">Costo Orario (€)</label>
                            <input type="number" step="0.01" class="form-control" id="cost_per_hour" name="cost_per_hour" value="<?= $resource['cost_per_hour'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cost_per_km" class="form-label">Costo per KM (€)</label>
                            <input type="number" step="0.01" class="form-control" id="cost_per_km" name="cost_per_km" value="<?= $resource['cost_per_km'] ?>">
                        </div>
                    </div>

                    <hr>
                    <h6>Specifiche Tecniche</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Targa (se veicolo)</label>
                            <input type="text" class="form-control" name="specs[plate]" value="<?= htmlspecialchars($resource['specs']['plate'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Capacità Volume (m³)</label>
                            <input type="number" step="0.1" class="form-control" name="specs[volume_capacity]" value="<?= htmlspecialchars($resource['specs']['volume_capacity'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= $resource['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Risorsa Attiva</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/resources" class="btn btn-outline-secondary">Annulla</a>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
