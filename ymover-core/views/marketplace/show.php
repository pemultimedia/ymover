<div class="mb-6">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/marketplace" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-white">
                    Marketplace
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 text-lg mx-1">chevron_right</span>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400"><?= htmlspecialchars($ad['title']) ?></span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden mb-6">
            <div class="relative h-64 md:h-96 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-9xl text-gray-300 dark:text-gray-500">
                    <?php
                    switch ($ad['type']) {
                        case 'vehicle': echo 'local_shipping'; break;
                        case 'equipment': echo 'handyman'; break;
                        case 'manpower': echo 'engineering'; break;
                        default: echo 'inventory_2';
                    }
                    ?>
                </span>
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-white/90 text-gray-800 shadow-sm backdrop-blur-sm">
                        <?= ucfirst($ad['type']) ?>
                    </span>
                </div>
            </div>
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?= htmlspecialchars($ad['title']) ?></h1>
                        <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm mb-2">
                            <span class="material-symbols-outlined text-lg mr-1">location_on</span>
                            <?= htmlspecialchars($ad['city']) ?>
                            <span class="mx-2">•</span>
                            <span>Pubblicato il <?= date('d/m/Y', strtotime($ad['created_at'])) ?></span>
                        </div>
                        <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
                            <span class="material-symbols-outlined text-lg mr-1">calendar_month</span>
                            Disponibile dal <?= date('d/m/Y', strtotime($ad['available_from'])) ?> al <?= date('d/m/Y', strtotime($ad['available_to'])) ?>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Prezzo Richiesto</p>
                        <p class="text-2xl font-bold text-primary">€ <?= number_format((float)$ad['price_fixed'], 2, ',', '.') ?></p>
                    </div>
                </div>

                <div class="prose dark:prose-invert max-w-none mb-8">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Descrizione</h3>
                    <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line"><?= nl2br(htmlspecialchars($ad['description'])) ?></p>
                </div>

                <?php if (isset($_SESSION['tenant_id']) && $ad['tenant_id'] == $_SESSION['tenant_id']): ?>
                    <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="/marketplace/edit?id=<?= $ad['id'] ?>" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <span class="material-symbols-outlined text-lg mr-2">edit</span>
                            Modifica
                        </a>
                        <a href="/marketplace/delete?id=<?= $ad['id'] ?>" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 bg-white dark:bg-gray-700 border border-red-200 dark:border-red-900/50 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" onclick="return confirm('Sei sicuro di voler eliminare questo annuncio?')">
                            <span class="material-symbols-outlined text-lg mr-2">delete</span>
                            Elimina
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden p-6 mb-6">
            <h5 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Contatta Inserzionista</h5>
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xl font-bold text-gray-600 dark:text-gray-300">
                    <?= strtoupper(substr($ad['user_name'] ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <p class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($ad['user_name'] ?? 'Utente YMover') ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Partner Verificato</p>
                </div>
            </div>
            <button class="w-full py-3 px-4 rounded-lg bg-primary text-white font-bold text-sm hover:bg-primary/90 transition-colors shadow-sm mb-3">
                Invia Messaggio
            </button>
            <button class="w-full py-3 px-4 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Mostra Numero
            </button>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">verified_user</span>
                <div>
                    <h6 class="font-bold text-blue-900 dark:text-blue-100 text-sm mb-1">Transazione Sicura</h6>
                    <p class="text-xs text-blue-800 dark:text-blue-200">Tutti i partner su YMover sono verificati. Contatta il supporto se riscontri problemi.</p>
                </div>
            </div>
        </div>
    </div>
</div>
