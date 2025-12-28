<div class="mb-6" x-data="inventoryEngine(<?= $request['id'] ?>)">
    <p class="text-sm font-medium text-primary">Trattativa #TR-<?= $request['id'] ?></p>
    <div class="flex flex-wrap items-center justify-between gap-4 mt-1">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dettaglio Trattativa: <?= htmlspecialchars($customer['name']) ?></h1>
        <div class="flex items-center gap-2">
            <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-100 dark:bg-gray-700 dark:text-white text-gray-900 text-sm font-bold leading-normal tracking-[0.015em] gap-2">
                <span class="material-symbols-outlined text-lg">edit</span>
                <span>Modifica</span>
            </button>
            <?php if ($request['status'] === 'confirmed'): ?>
                <button @click="openScheduleModal = true" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-600 text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
                    <span class="material-symbols-outlined text-lg">calendar_month</span>
                    <span>Pianifica</span>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start" x-data="{ openScheduleModal: false, openStopModal: false, activeTab: 'inventory' }">
    <div class="lg:col-span-2 flex flex-col gap-6">
        <!-- Dati Cliente e Stato -->
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dati Cliente e Stato</h2>
                <form action="/requests/update-status" method="POST" class="flex items-center gap-2">
                    <input type="hidden" name="id" value="<?= $request['id'] ?>">
                    <select name="status" class="rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary focus:border-primary" onchange="this.form.submit()">
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
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nome Cliente</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($customer['name']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Stato Trattativa</p>
                    <?php
                        $statusClasses = match($request['status']) {
                            'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                            'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300'
                        };
                    ?>
                    <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded-full text-xs font-medium <?= $statusClasses ?>">
                        <?= ucfirst(str_replace('_', ' ', $request['status'])) ?>
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                    <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($customer['email'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Telefono</p>
                    <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($customer['phone'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>

        <!-- Inventory Engine Section -->
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="flex -mb-px px-6 gap-6 overflow-x-auto">
                    <button @click="activeTab = 'inventory'" :class="activeTab === 'inventory' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-primary'" class="flex items-center gap-2 whitespace-nowrap px-1 py-4 border-b-2 text-sm font-semibold transition-colors">
                        <span class="material-symbols-outlined text-lg">inventory_2</span>
                        <span>Inventario e Logistica</span>
                    </button>
                    <button @click="activeTab = 'planning'" :class="activeTab === 'planning' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-primary'" class="flex items-center gap-2 whitespace-nowrap px-1 py-4 border-b-2 text-sm font-semibold transition-colors">
                        <span class="material-symbols-outlined text-lg">calendar_month</span>
                        <span>Pianificazione</span>
                    </button>
                </div>
            </div>

            <div class="p-6" x-show="activeTab === 'inventory'">
                <!-- Versions Tabs -->
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-2 overflow-x-auto pb-2">
                        <template x-for="version in versions" :key="version.id">
                            <button @click="selectVersion(version)" :class="currentVersion && currentVersion.id === version.id ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200'" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">
                                <span x-text="version.name"></span>
                                <span class="ml-2 opacity-80" x-text="formatVolume(version.total_volume) + ' m³'"></span>
                            </button>
                        </template>
                        <button @click="createVersion" class="px-4 py-2 rounded-lg text-sm font-medium border border-dashed border-gray-300 text-gray-500 hover:border-primary hover:text-primary transition-colors whitespace-nowrap">
                            + Nuova Versione
                        </button>
                    </div>
                </div>

                <!-- Stops / Località -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <template x-for="(stop, index) in stops" :key="stop.id">
                        <div class="flex gap-4 items-start p-4 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                            <div :class="index === 0 ? 'bg-primary/10 text-primary' : 'bg-green-500/10 text-green-600'" class="flex-shrink-0 size-10 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined" x-text="index === 0 ? 'move_up' : 'move_down'"></span>
                            </div>
                            <div class="flex-1 space-y-1">
                                <p class="font-semibold" :class="index === 0 ? 'text-primary' : 'text-green-600'" x-text="index === 0 ? 'Partenza' : 'Destinazione'"></p>
                                <p class="text-sm text-gray-900 dark:text-white" x-text="stop.address_full"></p>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span x-text="'Piano: ' + stop.floor"></span>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">elevator</span>
                                        <span x-text="'Ascensore: ' + (stop.elevator_status === 'yes' ? 'Sì' : 'No')"></span>
                                    </div>
                                </div>
                            </div>
                            <button @click="if(confirm('Rimuovere stop?')) window.location.href='/requests/remove-stop?id='+stop.id+'&request_id=<?= $request['id'] ?>'" class="text-gray-400 hover:text-red-500">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </template>
                    <button @click="openStopModal = true" class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 text-gray-400 hover:border-primary hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">add_location_alt</span>
                        <span class="text-sm font-medium">Aggiungi Località</span>
                    </button>
                </div>

                <!-- Inventory Table -->
                <div x-show="!loading && currentVersion">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Dettaglio Inventario</h3>
                        <button @click="createBlock" class="text-primary text-sm font-bold hover:underline">+ Aggiungi Stanza</button>
                    </div>

                    <div class="space-y-6">
                        <template x-for="block in currentVersion?.blocks" :key="block.id">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 flex justify-between items-center">
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="block.name"></span>
                                    <span class="text-xs text-gray-500" x-text="block.items.length + ' oggetti'"></span>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left">
                                        <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50">
                                            <tr>
                                                <th class="px-4 py-3">Articolo</th>
                                                <th class="px-4 py-3 text-center">Qta</th>
                                                <th class="px-4 py-3 text-right">Vol (m³)</th>
                                                <th class="px-4 py-3 text-right">Azioni</th>
                                            </tr>
                                        </thead>
                                        <tbody :data-block-id="block.id" class="sortable-items divide-y divide-gray-100 dark:divide-gray-700">
                                            <template x-for="item in block.items" :key="item.id">
                                                <tr :data-item-id="item.id" class="item-row hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                                    <td class="px-4 py-3">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-gray-300 cursor-move">☰</span>
                                                            <span class="font-medium text-gray-900 dark:text-white" x-text="item.description"></span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300" x-text="item.quantity"></td>
                                                    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300" x-text="formatVolume(item.volume_m3)"></td>
                                                    <td class="px-4 py-3 text-right">
                                                        <button @click="editItem(item)" class="text-gray-400 hover:text-primary mr-2">
                                                            <span class="material-symbols-outlined text-lg">edit</span>
                                                        </button>
                                                        <button @click="removeItem(item.id)" class="text-gray-400 hover:text-red-500">
                                                            <span class="material-symbols-outlined text-lg">delete</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                            <!-- Add Item Row -->
                                            <tr class="bg-gray-50/30 dark:bg-gray-800/30 no-drag">
                                                <td class="px-4 py-2">
                                                    <input type="text" class="w-full bg-transparent border-none focus:ring-0 text-sm placeholder:text-gray-400" placeholder="Nuovo oggetto..." @keydown.enter="addItem(block.id, $event.target)">
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    <input type="number" class="w-12 bg-transparent border-none text-center text-sm focus:ring-0" value="1">
                                                </td>
                                                <td class="px-4 py-2 text-right">
                                                    <input type="number" step="0.01" class="w-16 bg-transparent border-none text-right text-sm focus:ring-0" placeholder="0.00">
                                                </td>
                                                <td class="px-4 py-2 text-right">
                                                    <button @click="addItemFromRow(block.id, $el)" class="text-primary font-bold text-xs">Aggiungi</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Summary Footer -->
                    <div class="mt-8 flex flex-wrap justify-between items-center gap-6 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                        <div class="flex gap-8">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Volume Totale</p>
                                <p class="text-2xl font-black text-gray-900 dark:text-white" x-text="formatVolume(currentVersion?.total_volume) + ' m³'"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Operatori Stimati</p>
                                <p class="text-2xl font-black text-gray-900 dark:text-white">3</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Valore Preventivo</p>
                            <p class="text-3xl font-black text-primary">€ <?= number_format((float)($quotes[0]['amount_total'] ?? 0), 2, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6" x-show="activeTab === 'planning'">
                <p class="text-gray-500 text-center py-12">Funzionalità di pianificazione in arrivo...</p>
            </div>
        </div>
    </div>

    <!-- Sidebar: Annotations & Quotes -->
    <aside class="lg:col-span-1 flex flex-col gap-6">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden sticky top-24">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="flex -mb-px px-4 gap-4 overflow-x-auto">
                    <button class="flex items-center gap-2 whitespace-nowrap px-3 py-4 border-b-2 border-primary text-sm font-semibold text-primary">
                        <span class="material-symbols-outlined text-lg">sticky_note_2</span>
                        <span>Note</span>
                    </button>
                    <button class="flex items-center gap-2 whitespace-nowrap px-3 py-4 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-lg">request_quote</span>
                        <span>Preventivi</span>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4 mb-6">
                    <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-400 mb-1">Note Interne</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 italic"><?= htmlspecialchars($request['internal_notes'] ?? 'Nessuna nota.') ?></p>
                    </div>
                    
                    <template x-for="quote in <?= json_encode($quotes) ?>" :key="quote.id">
                        <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="'#' + quote.id"></p>
                                <p class="text-xs text-gray-500" x-text="'€ ' + parseFloat(quote.amount_total).toLocaleString('it-IT', {minimumFractionDigits: 2})"></p>
                            </div>
                            <span :class="quote.status === 'accepted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full" x-text="quote.status"></span>
                        </div>
                    </template>
                </div>
                
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <textarea class="w-full rounded-lg border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-3 text-sm placeholder:text-gray-400 focus:ring-primary focus:border-primary" placeholder="Aggiungi una nota..." rows="3"></textarea>
                    <button class="mt-3 w-full bg-primary text-white font-bold py-2 rounded-lg text-sm hover:bg-primary/90 transition-colors">Salva Nota</button>
                </div>
            </div>
        </div>
    </aside>

    <!-- Modals -->
    <template x-if="openStopModal">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div @click.away="openStopModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white">Aggiungi Località (Stop)</h5>
                    <button @click="openStopModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form action="/requests/add-stop" method="POST" class="p-6 space-y-4">
                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Indirizzo Completo</label>
                        <input type="text" name="address_full" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" required placeholder="Via Roma 1, Milano">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Piano</label>
                            <input type="number" name="floor" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" value="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ascensore</label>
                            <select name="elevator_status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary">
                                <option value="unknown">Sconosciuto</option>
                                <option value="yes">Sì</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openStopModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 rounded-lg">Annulla</button>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary/90 shadow-sm">Salva Stop</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <template x-if="openScheduleModal">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div @click.away="openScheduleModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white">Pianifica Lavoro #<?= $request['id'] ?></h5>
                    <button @click="openScheduleModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form action="/calendar/store" method="POST" class="p-6 space-y-4">
                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                    <input type="hidden" name="type" value="job">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Titolo Evento</label>
                        <input type="text" name="title" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" value="Trasloco <?= htmlspecialchars($customer['name']) ?>" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Inizio</label>
                            <input type="datetime-local" name="start_datetime" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fine</label>
                            <input type="datetime-local" name="end_datetime" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" required>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="openScheduleModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 rounded-lg">Annulla</button>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm">Pianifica</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Edit Item Modal -->
    <template x-if="editItemModal">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div @click.away="editItemModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white">Modifica Oggetto</h5>
                    <button @click="editItemModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrizione</label>
                        <input type="text" x-model="editingItem.description" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantità</label>
                            <input type="number" x-model="editingItem.quantity" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Volume (m³)</label>
                            <input type="number" step="0.001" x-model="editingItem.volume_m3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="editItemModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 rounded-lg">Annulla</button>
                        <button type="button" @click="saveItem" class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary/90 shadow-sm">Salva</button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('inventoryEngine', (requestId) => ({
        requestId: requestId,
        versions: [],
        currentVersion: null,
        loading: true,
        editItemModal: false,
        editingItem: null,
        stops: <?= json_encode($stops) ?>,

        init() {
            this.loadInventory();
            this.$nextTick(() => {
                this.initSortable();
            });
        },

        initSortable() {
            const self = this;
            document.querySelectorAll('.sortable-items').forEach(el => {
                new Sortable(el, {
                    group: 'inventory',
                    draggable: '.item-row',
                    filter: '.no-drag',
                    handle: '.cursor-move',
                    animation: 150,
                    onEnd: async (evt) => {
                        const itemId = evt.item.getAttribute('data-item-id');
                        const newBlockId = evt.to.getAttribute('data-block-id');
                        
                        if (evt.from !== evt.to) {
                            await self.moveItem(itemId, newBlockId);
                        }
                    }
                });
            });
        },

        async moveItem(itemId, blockId) {
            try {
                await fetch('/api/inventory/item/move', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: itemId, block_id: blockId })
                });
                this.loadInventory();
            } catch (e) {
                alert('Errore spostamento oggetto');
            }
        },

        editItem(item) {
            this.editingItem = JSON.parse(JSON.stringify(item));
            this.editItemModal = true;
        },

        async saveItem() {
            try {
                await fetch('/api/inventory/item/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.editingItem)
                });
                this.editItemModal = false;
                this.loadInventory();
            } catch (e) {
                alert('Errore salvataggio oggetto');
            }
        },

        async loadInventory() {
            this.loading = true;
            try {
                const response = await fetch(`/api/inventory?request_id=${this.requestId}`);
                const data = await response.json();
                this.versions = data.versions;
                
                if (this.versions.length > 0) {
                    this.currentVersion = this.versions[0]; 
                }
            } catch (error) {
                console.error('Error loading inventory:', error);
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
            
            inputs[0].value = '';
            inputs[1].value = '1';
            inputs[2].value = '';
            inputs[0].focus();
        },

        async addItem(blockId, description, quantity = 1, volume = 0) {
            if (typeof description === 'object') { 
                const input = description;
                description = input.value;
                if (!description) return;
                input.value = ''; 
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

<style>
[x-cloak] { display: none !important; }
.cursor-move { cursor: move; }
</style>
