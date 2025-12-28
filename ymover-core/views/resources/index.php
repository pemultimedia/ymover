<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestione Risorse</h1>
    <a href="/resources/create" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2 hover:bg-primary/90 transition-colors">
        <span class="material-symbols-outlined text-lg">add</span>
        <span>Nuova Risorsa</span>
    </a>
</div>

<div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 font-medium">Nome</th>
                    <th class="px-6 py-3 font-medium">Tipo</th>
                    <th class="px-6 py-3 font-medium">Costo/h</th>
                    <th class="px-6 py-3 font-medium">Costo/km</th>
                    <th class="px-6 py-3 font-medium">Stato</th>
                    <th class="px-6 py-3 font-medium text-right">Azioni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if (empty($resources)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Nessuna risorsa trovata.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($resources as $r): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($r['name']) ?></div>
                                <?php 
                                    $specs = json_decode($r['specs'] ?? '{}', true);
                                    if ($r['type'] === 'vehicle' && isset($specs['plate'])): 
                                ?>
                                    <small class="text-gray-500 dark:text-gray-400">Targa: <?= htmlspecialchars($specs['plate']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                    <?= $r['type'] === 'vehicle' ? 'Veicolo' : ($r['type'] === 'employee' ? 'Personale' : 'Attrezzatura') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">€ <?= number_format((float)$r['cost_per_hour'], 2, ',', '.') ?></td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">€ <?= number_format((float)$r['cost_per_km'], 2, ',', '.') ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $r['is_active'] ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' ?>">
                                    <?= $r['is_active'] ? 'Attiva' : 'Inattiva' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/resources/edit?id=<?= $r['id'] ?>" class="text-gray-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <a href="/resources/delete?id=<?= $r['id'] ?>" class="text-gray-400 hover:text-red-500 transition-colors" onclick="return confirm('Sicuro di voler eliminare questa risorsa?')">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
