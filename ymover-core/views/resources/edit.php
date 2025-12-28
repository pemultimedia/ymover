<div class="mb-6">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/resources" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-white">
                    Risorse
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 text-lg mx-1">chevron_right</span>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Modifica Risorsa</span>
                </div>
            </li>
        </ol>
    </nav>
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Modifica Risorsa: <?= htmlspecialchars($resource['name']) ?></h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6">
                <form action="/resources/update" method="POST" class="space-y-6">
                    <input type="hidden" name="id" value="<?= $resource['id'] ?>">
                    
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome Risorsa</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($resource['name']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" required>
                    </div>

                    <div>
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo Risorsa</label>
                        <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" required>
                            <option value="vehicle" <?= $resource['type'] === 'vehicle' ? 'selected' : '' ?>>Veicolo</option>
                            <option value="employee" <?= $resource['type'] === 'employee' ? 'selected' : '' ?>>Personale (Esterno/Collaboratore)</option>
                            <option value="equipment" <?= $resource['type'] === 'equipment' ? 'selected' : '' ?>>Attrezzatura (Scala, Transpallet, etc.)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="cost_per_hour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Costo Orario (€)</label>
                            <input type="number" step="0.01" id="cost_per_hour" name="cost_per_hour" value="<?= $resource['cost_per_hour'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                        </div>
                        <div>
                            <label for="cost_per_km" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Costo per KM (€)</label>
                            <input type="number" step="0.01" id="cost_per_km" name="cost_per_km" value="<?= $resource['cost_per_km'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h6 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Specifiche Tecniche</h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Targa (se veicolo)</label>
                                <input type="text" name="specs[plate]" value="<?= htmlspecialchars($resource['specs']['plate'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Capacità Volume (m³)</label>
                                <input type="number" step="0.1" name="specs[volume_capacity]" value="<?= htmlspecialchars($resource['specs']['volume_capacity'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center mb-4">
                        <input id="is_active" type="checkbox" name="is_active" value="1" <?= $resource['is_active'] ? 'checked' : '' ?> class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Risorsa Attiva</label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="/resources" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">Annulla</a>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary/90 shadow-sm transition-colors">Salva Modifiche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
