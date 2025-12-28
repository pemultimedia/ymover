<div class="mb-6">
    <a href="/storage" class="inline-flex items-center text-sm text-gray-500 hover:text-primary mb-2">
        <span class="material-symbols-outlined mr-1 text-lg">arrow_back</span>
        Torna alla lista
    </a>
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">Contratto #<?= $contract['id'] ?></h1>
            <p class="text-gray-500 dark:text-gray-400">Cliente: <?= htmlspecialchars($contract['customer_name']) ?> | Magazzino: <?= htmlspecialchars($contract['warehouse_name']) ?></p>
        </div>
        <div class="flex gap-2">
            <!-- Action buttons could go here -->
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Contract Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Dettagli Contratto</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Data Inizio</p>
                    <p class="font-medium text-gray-900 dark:text-white"><?= date('d/m/Y', strtotime($contract['start_date'])) ?></p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Data Fine Prevista</p>
                    <p class="font-medium text-gray-900 dark:text-white"><?= $contract['end_date_expected'] ? date('d/m/Y', strtotime($contract['end_date_expected'])) : '-' ?></p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Ciclo Fatturazione</p>
                    <p class="font-medium text-gray-900 dark:text-white"><?= ucfirst($contract['billing_cycle']) ?></p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Tipo Pagamento</p>
                    <p class="font-medium text-gray-900 dark:text-white"><?= ucfirst($contract['payment_type']) ?></p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Prezzo Periodico</p>
                    <p class="font-medium text-gray-900 dark:text-white">€ <?= number_format((float)$contract['price_per_period'], 2) ?></p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Stato</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <?= ucfirst($contract['status']) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Movements List -->
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Movimenti</h2>
                <button onclick="document.getElementById('movementModal').classList.remove('hidden')" class="px-3 py-1.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors">
                    Registra Movimento
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Data</th>
                            <th scope="col" class="px-6 py-3">Tipo</th>
                            <th scope="col" class="px-6 py-3">Volume (m³)</th>
                            <th scope="col" class="px-6 py-3">Note</th>
                            <th scope="col" class="px-6 py-3">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($movements)): ?>
                            <?php foreach ($movements as $movement): ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">
                                        <?= date('d/m/Y H:i', strtotime($movement['date'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($movement['type'] === 'in'): ?>
                                            <span class="text-green-600 font-bold flex items-center"><span class="material-symbols-outlined text-sm mr-1">arrow_downward</span> IN</span>
                                        <?php else: ?>
                                            <span class="text-red-600 font-bold flex items-center"><span class="material-symbols-outlined text-sm mr-1">arrow_upward</span> OUT</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 font-medium">
                                        <?= number_format((float)$movement['total_volume_m3'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 truncate max-w-xs">
                                        <?= htmlspecialchars($movement['notes'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="/storage/waybill?movement_id=<?= $movement['id'] ?>" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                            <span class="material-symbols-outlined text-sm mr-1">description</span> Bolla
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center">Nessun movimento registrato.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar / Summary -->
    <div class="space-y-6">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-100 dark:border-blue-800">
            <h3 class="font-bold text-blue-900 dark:text-blue-100 mb-2">Riepilogo Stoccaggio</h3>
            <div class="flex items-end gap-2 mb-1">
                <span class="text-3xl font-bold text-blue-700 dark:text-blue-300">
                    <?php 
                        $currentVolume = 0;
                        foreach ($movements as $m) {
                            if ($m['type'] === 'in') $currentVolume += $m['total_volume_m3'];
                            else $currentVolume -= $m['total_volume_m3'];
                        }
                        echo number_format($currentVolume, 2);
                    ?>
                </span>
                <span class="text-sm text-blue-600 dark:text-blue-400 mb-1">m³ occupati</span>
            </div>
        </div>
    </div>
</div>

<!-- Register Movement Modal -->
<div id="movementModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Registra Movimento</h3>
            <button onclick="document.getElementById('movementModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="/storage/movement/store" method="POST">
            <input type="hidden" name="contract_id" value="<?= $contract['id'] ?>">
            
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo Movimento</label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="in" checked class="text-primary focus:ring-primary">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Entrata (IN)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="out" class="text-primary focus:ring-primary">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Uscita (OUT)</span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Articoli (JSON)</label>
                <textarea name="items_json" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder='[{"desc": "Scatola", "vol": 0.1, "qty": 10}]' required></textarea>
                <p class="mt-1 text-xs text-gray-500">Formato JSON: [{"desc": "...", "vol": 0.5, "qty": 1}]</p>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note</label>
                <textarea name="notes" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('movementModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Annulla</button>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary/90">Registra</button>
            </div>
        </form>
    </div>
</div>
