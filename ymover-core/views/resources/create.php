<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/resources">Risorse</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuova Risorsa</li>
        </ol>
    </nav>
    <h1>Aggiungi Risorsa</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="/resources/store" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Risorsa (es. Furgone Iveco 35C15)</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo Risorsa</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="vehicle">Veicolo</option>
                            <option value="employee">Personale (Esterno/Collaboratore)</option>
                            <option value="equipment">Attrezzatura (Scala, Transpallet, etc.)</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cost_per_hour" class="form-label">Costo Orario (€)</label>
                            <input type="number" step="0.01" class="form-control" id="cost_per_hour" name="cost_per_hour" value="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cost_per_km" class="form-label">Costo per KM (€)</label>
                            <input type="number" step="0.01" class="form-control" id="cost_per_km" name="cost_per_km" value="0.00">
                        </div>
                    </div>

                    <hr>
                    <h6>Specifiche Tecniche</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Targa (se veicolo)</label>
                            <input type="text" class="form-control" name="specs[plate]">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Capacità Volume (m³)</label>
                            <input type="number" step="0.1" class="form-control" name="specs[volume_capacity]">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Risorsa Attiva</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/resources" class="btn btn-outline-secondary">Annulla</a>
                        <button type="submit" class="btn btn-primary">Salva Risorsa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
