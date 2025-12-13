<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Nuova Richiesta</h2>
    <a href="/requests" class="btn btn-outline-secondary">Indietro</a>
</div>

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <form action="/requests/store" method="POST">
            <div class="mb-3">
                <label class="form-label">Fonte (Source)</label>
                <select name="source" class="form-select">
                    <option value="manual">Manuale</option>
                    <option value="web">Web</option>
                    <option value="phone">Telefono</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Note Interne</label>
                <textarea name="internal_notes" class="form-control" rows="4"></textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">Salva Richiesta</button>
            </div>
        </form>
    </div>
</div>
