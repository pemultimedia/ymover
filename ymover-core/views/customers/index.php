<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em] min-w-72">Archivio Clienti</p>
    <a href="/customers/create" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
        <span class="truncate">+ Aggiungi Cliente</span>
    </a>
</div>

<!-- SearchBar -->
<div class="mb-6">
    <label class="flex flex-col min-w-40 h-12 w-full">
        <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
            <div class="text-gray-500 flex border-none bg-gray-100 dark:bg-gray-800 items-center justify-center pl-4 rounded-l-lg border-r-0">
                <span class="material-symbols-outlined">search</span>
            </div>
            <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-gray-200 focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-gray-100 dark:bg-gray-800 h-full placeholder:text-gray-500 px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal" placeholder="Cerca cliente per nome, email..." value=""/>
        </div>
    </label>
</div>

<!-- Table -->
<div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-4 text-left text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">Nome Cliente</th>
                    <th class="px-6 py-4 text-left text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">Tipo</th>
                    <th class="px-6 py-4 text-left text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">Codice Fiscale / P.IVA</th>
                    <th class="px-6 py-4 text-left text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">Azioni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-16 px-6">
                            <div class="flex flex-col items-center gap-4">
                                <span class="material-symbols-outlined text-5xl text-gray-400">person_off</span>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Nessun cliente trovato</h3>
                                <p class="text-sm text-gray-500">Prova a modificare la ricerca o aggiungi un nuovo cliente.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors" onclick="window.location='/customers/show?id=<?= $customer['id'] ?>'">
                            <td class="px-6 py-4 text-gray-900 dark:text-white text-sm font-bold leading-normal"><?= htmlspecialchars($customer['name']) ?></td>
                            <td class="px-6 py-4 text-sm font-normal leading-normal">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold <?= $customer['type'] === 'company' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' ?>">
                                    <?= $customer['type'] === 'company' ? 'Azienda' : 'Privato' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal"><?= htmlspecialchars($customer['tax_code'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="/customers/show?id=<?= $customer['id'] ?>" class="text-primary hover:underline mr-3">Visualizza</a>
                                <a href="/customers/edit?id=<?= $customer['id'] ?>" class="text-gray-500 dark:text-gray-400 hover:underline">Modifica</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
