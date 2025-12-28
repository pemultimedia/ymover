<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="/storage" class="inline-flex items-center text-sm text-gray-500 hover:text-primary mb-2">
            <span class="material-symbols-outlined mr-1 text-lg">arrow_back</span>
            Torna alla lista
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Nuovo Contratto di Deposito</h1>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <form action="/storage/store" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
                    <select id="customer_id" name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        <option value="">Seleziona Cliente</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id'] ?>"><?= htmlspecialchars($customer['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="warehouse_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Magazzino</label>
                    <select id="warehouse_id" name="warehouse_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        <option value="">Seleziona Magazzino</option>
                        <?php foreach ($warehouses as $warehouse): ?>
                            <option value="<?= $warehouse['id'] ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data Inizio</label>
                    <input type="date" id="start_date" name="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                </div>
                <div>
                    <label for="billing_cycle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ciclo Fatturazione</label>
                    <select id="billing_cycle" name="billing_cycle" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="monthly">Mensile</option>
                        <option value="weekly">Settimanale</option>
                        <option value="daily">Giornaliero</option>
                    </select>
                </div>
                <div>
                    <label for="payment_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo Pagamento</label>
                    <select id="payment_type" name="payment_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="prepaid">Anticipato</option>
                        <option value="postpaid">Posticipato</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="price_per_period" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prezzo per Periodo (€)</label>
                    <input type="number" step="0.01" id="price_per_period" name="price_per_period" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="0.00" required>
                </div>
                <div>
                    <label for="insurance_declared_value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valore Assicurato (€)</label>
                    <input type="number" step="0.01" id="insurance_declared_value" name="insurance_declared_value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="0.00">
                </div>
                <div>
                    <label for="insurance_cost" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Costo Assicurazione (€)</label>
                    <input type="number" step="0.01" id="insurance_cost" name="insurance_cost" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="0.00">
                </div>
            </div>

            <div>
                <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note</label>
                <textarea id="notes" name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="/storage" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">Annulla</a>
                <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary/90 shadow-sm transition-colors">Crea Contratto</button>
            </div>
        </form>
    </div>
</div>
