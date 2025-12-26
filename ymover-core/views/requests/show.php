<div class="row" x-data="inventoryEngine(<?= $request['id'] ?>)">
    <!-- Left Column: Info & Logistics -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Cliente</h6>
                <form action="/requests/update-status" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="<?= $request['id'] ?>">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                        <?php 
                        $statuses = ['new', 'contacted', 'survey_done', 'quoted', 'confirmed', 'completed', 'cancelled', 'archived'];
                        foreach ($statuses as $status): ?>
                            <option value="<?= $status ?>" <?= $request['status'] === $status ? 'selected' : '' ?>>
                                <?= ucfirst(str_replace('_', ' ', $status)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($customer['name']) ?></h5>
                <p class="text-muted small mb-2">ID: #<?= $request['id'] ?></p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-secondary btn-sm">Modifica Anagrafica</button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Logistica (Stops)</h6>
                <button class="btn btn-sm btn-outline-primary" @click="addStopModal = true">+ Stop</button>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (empty($stops)): ?>
                        <li class="list-group-item text-muted small text-center py-3">
                            Nessun indirizzo inserito.
                        </li>
                    <?php else: ?>
                        <?php foreach ($stops as $stop): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold"><?= htmlspecialchars($stop['address_full']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($stop['city'] ?? '') ?> - Piano: <?= $stop['floor'] ?></small>
                                </div>
                                <a href="/requests/remove-stop?id=<?= $stop['id'] ?>&request_id=<?= $request['id'] ?>" class="btn btn-sm text-danger" onclick="return confirm('Rimuovere questo stop?')">&times;</a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Note Interne</h6>
            </div>
            <div class="card-body">
                <textarea class="form-control" rows="5"><?= htmlspecialchars($request['internal_notes'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- Right Column: Inventory Engine -->
    <div class="col-md-8">
        <div class="card shadow-sm" style="min-height: 600px;">
            <div class="card-header bg-white p-0">
                <ul class="nav nav-tabs card-header-tabs m-0">
                    <template x-for="version in versions" :key="version.id">
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': currentVersion && currentVersion.id === version.id }" 
                               href="#" @click.prevent="selectVersion(version)">
                                <span x-text="version.name"></span>
                                <span class="badge bg-secondary ms-2" x-text="formatVolume(version.total_volume) + ' m³'"></span>
                            </a>
                        </li>
                    </template>
                    <li class="nav-item">
                        <a class="nav-link text-success" href="#" @click.prevent="createVersion">+ Nuova</a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body bg-light">
                <div x-show="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Caricamento Inventario...</p>
                </div>

                <div x-show="!loading && currentVersion">
                    <!-- Toolbar -->
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <button class="btn btn-sm btn-outline-success" @click="createBlock">+ Aggiungi Stanza/Blocco</button>
                        </div>
                        <div class="h5 mb-0">
                            Totale: <span class="fw-bold text-primary" x-text="formatVolume(currentVersion?.total_volume) + ' m³'"></span>
                        </div>
                    </div>

                    <!-- Blocks Accordion -->
                    <div class="accordion" id="inventoryAccordion">
                        <template x-for="block in currentVersion?.blocks" :key="block.id">
                            <div class="accordion-item mb-2 border rounded shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" :data-bs-target="'#collapse' + block.id" aria-expanded="true">
                                        <span class="fw-bold me-2" x-text="block.name"></span>
                                        <span class="badge bg-light text-dark border ms-auto" x-text="block.items.length + ' oggetti'"></span>
                                    </button>
                                </h2>
                                <div :id="'collapse' + block.id" class="accordion-collapse collapse show">
                                    <div class="accordion-body p-0">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 50%">Descrizione</th>
                                                    <th class="text-center">Qta</th>
                                                    <th class="text-end">Vol (m³)</th>
                                                    <th class="text-end">Azioni</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="item in block.items" :key="item.id">
                                                    <tr>
                                                        <td x-text="item.description"></td>
                                                        <td class="text-center" x-text="item.quantity"></td>
                                                        <td class="text-end" x-text="formatVolume(item.volume_m3)"></td>
                                                        <td class="text-end">
                                                            <button class="btn btn-sm btn-link text-danger p-0" @click="removeItem(item.id)">
                                                                &times;
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <!-- Add Item Row -->
                                                <tr class="bg-light">
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Nuovo oggetto..." 
                                                               @keydown.enter="addItem(block.id, $event.target)">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm text-center" value="1" style="width: 60px">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" class="form-control form-control-sm text-end" placeholder="0.00" style="width: 80px">
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-primary" @click="addItemFromRow(block.id, $el)">Add</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('inventoryEngine', (requestId) => ({
        requestId: requestId,
        versions: [],
        currentVersion: null,
        loading: true,
        addStopModal: false,

        init() {
            this.loadInventory();
        },

        async loadInventory() {
            this.loading = true;
            try {
                const response = await fetch(`/api/inventory?request_id=${this.requestId}`);
                const data = await response.json();
                this.versions = data.versions;
                
                // Select first version or restore selection
                if (this.versions.length > 0) {
                    // Ideally check for is_selected flag, for now take first
                    this.currentVersion = this.versions[0]; 
                }
            } catch (error) {
                console.error('Error loading inventory:', error);
                alert('Errore caricamento inventario');
            } finally {
                this.loading = false;
            }
        },

        selectVersion(version) {
            this.currentVersion = version;
        },

        formatVolume(vol) {
            return parseFloat(vol || 0).toFixed(2);
        },

        async createVersion() {
            const name = prompt("Nome della nuova versione (es. Solo Mobili):");
            if (!name) return;

            try {
                await fetch('/api/inventory/version/create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ request_id: this.requestId, name: name })
                });
                this.loadInventory();
            } catch (e) {
                alert('Errore creazione versione');
            }
        },

        async createBlock() {
            if (!this.currentVersion) return;
            const name = prompt("Nome della stanza/gruppo (es. Cucina):");
            if (!name) return;

            try {
                await fetch('/api/inventory/block/create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ version_id: this.currentVersion.id, name: name })
                });
                this.loadInventory();
            } catch (e) {
                alert('Errore creazione blocco');
            }
        },

        async addItemFromRow(blockId, btnElement) {
            const row = btnElement.closest('tr');
            const inputs = row.querySelectorAll('input');
            const description = inputs[0].value;
            const quantity = inputs[1].value;
            const volume = inputs[2].value;

            if (!description) return;

            await this.addItem(blockId, description, quantity, volume);
            
            // Reset inputs
            inputs[0].value = '';
            inputs[1].value = '1';
            inputs[2].value = '';
            inputs[0].focus();
        },

        async addItem(blockId, description, quantity = 1, volume = 0) {
            if (typeof description === 'object') { 
                // Handle enter key event
                const input = description;
                description = input.value;
                if (!description) return;
                input.value = ''; // Reset immediately for UX
            }

            try {
                await fetch('/api/inventory/item/add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        block_id: blockId, 
                        description: description,
                        quantity: quantity,
                        volume_m3: volume
                    })
                });
                this.loadInventory();
            } catch (e) {
                alert('Errore aggiunta oggetto');
            }
        },

        async removeItem(itemId) {
            if (!confirm('Rimuovere oggetto?')) return;
            
            try {
                await fetch('/api/inventory/item/remove', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: itemId })
                });
                this.loadInventory();
            } catch (e) {
                alert('Errore rimozione oggetto');
            }
        }
    }));
});
</script>

<!-- Add Stop Modal -->
<div class="modal fade" :class="{ 'show d-block': addStopModal }" tabindex="-1" style="background: rgba(0,0,0,0.5)" x-show="addStopModal" x-cloak>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aggiungi Stop</h5>
                <button type="button" class="btn-close" @click="addStopModal = false"></button>
            </div>
            <form action="/requests/add-stop" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Indirizzo Completo</label>
                        <input type="text" name="address_full" class="form-control" required placeholder="Via Roma 1, Milano">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Città</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Piano</label>
                            <input type="number" name="floor" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ascensore</label>
                            <select name="elevator_status" class="form-select">
                                <option value="unknown">Sconosciuto</option>
                                <option value="yes">Sì</option>
                                <option value="no">No</option>
                                <option value="external_needed">Serve Esterno</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="addStopModal = false">Annulla</button>
                    <button type="submit" class="btn btn-primary">Salva Stop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
