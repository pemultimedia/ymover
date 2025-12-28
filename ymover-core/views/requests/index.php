<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <h1 class="text-3xl font-black tracking-tighter text-text-light dark:text-text-dark"><?= __('requests') ?></h1>
    <a href="/requests/create" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white gap-2 text-sm font-bold shadow-sm hover:bg-primary/90">
        <span class="material-symbols-outlined">add</span>
        <span class="truncate"><?= __('new_request') ?></span>
    </a>
</div>

<!-- Controls Bar -->
<div class="mb-6 rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-gray-800/50 p-4 shadow-sm">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <!-- Chips -->
        <div class="flex flex-wrap items-center gap-3 lg:col-span-2">
            <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-background-light dark:bg-background-dark px-3 hover:bg-gray-200 dark:hover:bg-gray-700">
                <p class="text-sm font-medium text-text-light dark:text-text-dark">Stato: Tutti</p>
                <span class="material-symbols-outlined text-text-muted-light dark:text-text-muted-dark">expand_more</span>
            </button>
            <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-background-light dark:bg-background-dark px-3 hover:bg-gray-200 dark:hover:bg-gray-700">
                <p class="text-sm font-medium text-text-light dark:text-text-dark">Operatore: Tutti</p>
                <span class="material-symbols-outlined text-text-muted-light dark:text-text-muted-dark">expand_more</span>
            </button>
        </div>
        <!-- SearchBar -->
        <div class="lg:col-span-2">
            <label class="flex h-10 w-full flex-col">
                <div class="flex w-full flex-1 items-stretch rounded-lg">
                    <div class="flex items-center justify-center rounded-l-lg border border-r-0 border-gray-200 dark:border-gray-700 bg-background-light dark:bg-background-dark pl-3 text-gray-500">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                    <input class="form-input h-full w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg border border-l-0 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 px-3 text-sm placeholder:text-gray-500 focus:border-primary focus:ring-primary" placeholder="<?= __('search') ?>" value=""/>
                </div>
            </label>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400" scope="col">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400" scope="col">Nome Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400" scope="col">Data Richiesta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400" scope="col">Stato</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400" scope="col">Origine</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400" scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="6" class="text-center py-16 px-6">
                        <div class="flex flex-col items-center gap-4">
                            <span class="material-symbols-outlined text-5xl text-gray-400">search_off</span>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Nessuna richiesta trovata</h3>
                            <p class="text-sm text-gray-500">Prova a modificare i filtri o aggiungi una nuova richiesta.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($requests as $req): ?>
                    <tr class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150" onclick="window.location='/requests/show?id=<?= $req['id'] ?>'">
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">#<?= htmlspecialchars((string)$req['id']) ?></td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($req['customer_name']) ?></td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400"><?= date('d/m/Y H:i', strtotime($req['created_at'])) ?></td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <?php
                                $statusClasses = match($req['status']) {
                                    'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                                    'contacted' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-300',
                                    'quoted' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                                    'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                };
                            ?>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold <?= $statusClasses ?>">
                                <?= htmlspecialchars(ucfirst($req['status'])) ?>
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($req['source']) ?></td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <a href="/requests/show?id=<?= $req['id'] ?>" class="text-primary hover:underline font-medium"><?= __('view') ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
