<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="/warehouses" class="inline-flex items-center text-sm text-gray-500 hover:text-primary mb-2">
            <span class="material-symbols-outlined mr-1 text-lg">arrow_back</span>
            Torna alla lista
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Modifica Magazzino</h1>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <form action="/warehouses/update" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?= $warehouse['id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome Magazzino</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($warehouse['name']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                </div>
                <div>
                    <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codice Interno</label>
                    <input type="text" id="code" name="code" value="<?= htmlspecialchars($warehouse['code'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
            </div>

            <div>
                <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Indirizzo Completo</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($warehouse['address']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Città</label>
                    <input type="text" id="city" name="city" value="<?= htmlspecialchars($warehouse['city'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
                <div>
                    <label for="lat" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Latitudine</label>
                    <input type="number" step="0.00000001" id="lat" name="lat" value="<?= htmlspecialchars((string)$warehouse['lat']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
                <div>
                    <label for="lng" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Longitudine</label>
                    <input type="number" step="0.00000001" id="lng" name="lng" value="<?= htmlspecialchars((string)$warehouse['lng']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
            </div>

            <div>
                <label for="total_capacity_m3" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Capacità Totale (m³)</label>
                <input type="number" step="0.01" id="total_capacity_m3" name="total_capacity_m3" value="<?= htmlspecialchars((string)$warehouse['total_capacity_m3']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>

            <div class="flex items-center">
                <input id="is_active" name="is_active" type="checkbox" value="1" <?= $warehouse['is_active'] ? 'checked' : '' ?> class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Magazzino Attivo</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="/warehouses" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">Annulla</a>
                <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary/90 shadow-sm transition-colors">Salva Modifiche</button>
            </div>
        </form>
    </div>
</div>
