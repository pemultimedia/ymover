<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Gestione Magazzini</h1>
        <p class="text-gray-500 dark:text-gray-400">Configura i tuoi depositi e box.</p>
    </div>
    <a href="/warehouses/create" class="px-4 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-colors shadow-sm flex items-center">
        <span class="material-symbols-outlined mr-2">add_business</span>
        Nuovo Magazzino
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (!empty($warehouses)): ?>
        <?php foreach ($warehouses as $warehouse): ?>
            <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <span class="material-symbols-outlined">warehouse</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($warehouse['name']) ?></h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($warehouse['code'] ?? '') ?></p>
                        </div>
                    </div>
                    <?php if ($warehouse['is_active']): ?>
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Attivo</span>
                    <?php else: ?>
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inattivo</span>
                    <?php endif; ?>
                </div>
                
                <div class="space-y-2 mb-6">
                    <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <span class="material-symbols-outlined text-gray-400 text-lg mt-0.5">location_on</span>
                        <span><?= htmlspecialchars($warehouse['address']) ?><br><?= htmlspecialchars($warehouse['city'] ?? '') ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <span class="material-symbols-outlined text-gray-400 text-lg">inventory_2</span>
                        <span>Capacità: <strong><?= number_format((float)$warehouse['total_capacity_m3'], 2) ?> m³</strong></span>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="/warehouses/edit?id=<?= $warehouse['id'] ?>" class="flex-1 py-2 px-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        Modifica
                    </a>
                    <a href="/warehouses/delete?id=<?= $warehouse['id'] ?>" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" onclick="return confirm('Sei sicuro?')">
                        <span class="material-symbols-outlined">delete</span>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full text-center py-12 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
            <span class="material-symbols-outlined text-4xl text-gray-400 mb-2">warehouse</span>
            <p class="text-gray-500 dark:text-gray-400">Nessun magazzino configurato.</p>
        </div>
    <?php endif; ?>
</div>
