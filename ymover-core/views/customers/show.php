<div class="mb-6">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/customers" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-white">
                    Clienti
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 text-lg mx-1">chevron_right</span>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400"><?= htmlspecialchars($customer['name']) ?></span>
                </div>
            </li>
        </ol>
    </nav>
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($customer['name']) ?></h1>
        <a href="/customers/edit?id=<?= $customer['id'] ?>" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-100 dark:bg-gray-700 dark:text-white text-gray-900 text-sm font-bold leading-normal tracking-[0.015em] gap-2 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            <span class="material-symbols-outlined text-lg">edit</span>
            <span>Modifica</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h5 class="text-lg font-bold text-gray-900 dark:text-white">Dettagli Cliente</h5>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tipo</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $customer['type'] === 'company' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' ?>">
                        <?= $customer['type'] === 'company' ? 'Azienda' : 'Privato' ?>
                    </span>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Codice Fiscale / P.IVA</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($customer['tax_code'] ?? '-') ?></p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Note</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line"><?= htmlspecialchars($customer['notes'] ?? 'Nessuna nota.') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                <h5 class="text-lg font-bold text-gray-900 dark:text-white">Storico Richieste</h5>
                <a href="/requests/create?customer_id=<?= $customer['id'] ?>" class="flex items-center justify-center rounded-lg h-8 px-3 bg-primary text-white text-xs font-bold hover:bg-primary/90 transition-colors">
                    + Nuova Richiesta
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 font-medium">ID</th>
                            <th class="px-6 py-3 font-medium">Stato</th>
                            <th class="px-6 py-3 font-medium">Data</th>
                            <th class="px-6 py-3 font-medium text-right">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php if (empty($requests)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Nessuna richiesta trovata per questo cliente.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($requests as $req): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#<?= $req['id'] ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                            $statusClasses = match($req['status']) {
                                                'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                                                'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                            };
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClasses ?>">
                                            <?= ucfirst($req['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                        <?= date('d/m/Y', strtotime($req['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="/requests/show?id=<?= $req['id'] ?>" class="text-primary hover:text-primary/80 font-medium text-sm">Visualizza</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
