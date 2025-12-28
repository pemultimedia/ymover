<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Marketplace B2B</h1>
    <p class="text-gray-500 dark:text-gray-400">Trova o offri risorse (veicoli, attrezzature, personale) ad altri traslocatori.</p>
</div>

<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 mb-8">
    <h5 class="text-lg font-bold text-blue-900 dark:text-blue-100 mb-2">Novità: Marketplace YMover</h5>
    <p class="text-blue-800 dark:text-blue-200">Questa sezione ti permette di collaborare con altri professionisti del settore. Puoi noleggiare un elevatore o trovare un autista disponibile nella tua zona.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Add New Card -->
    <a href="/marketplace/create" class="bg-gray-50 dark:bg-gray-800/30 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center p-8 h-full min-h-[300px] hover:border-primary hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all cursor-pointer group">
        <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mb-4 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-3xl text-gray-400 group-hover:text-primary">add</span>
        </div>
        <h5 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Inserisci Annuncio</h5>
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">Hai risorse inutilizzate? Noleggiale ad altri partner.</p>
        <button class="py-2 px-6 rounded-lg bg-primary text-white font-bold text-sm hover:bg-primary/90 transition-colors shadow-sm">
            Crea Ora
        </button>
    </a>

    <?php if (!empty($ads)): ?>
        <?php foreach ($ads as $ad): ?>
            <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                <div class="relative h-48">
                    <img src="<?= htmlspecialchars($ad['image_url'] ?: 'https://via.placeholder.com/800x400?text=No+Image') ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($ad['title']) ?>">
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/90 text-gray-800 shadow-sm backdrop-blur-sm">
                            <?= ucfirst($ad['type']) ?>
                        </span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white mb-2"><?= htmlspecialchars($ad['title']) ?></h5>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 flex-grow">
                        <p class="mb-1">Disponibile a: <span class="font-medium text-gray-700 dark:text-gray-300"><?= htmlspecialchars($ad['location']) ?></span></p>
                        <p>Prezzo: <span class="font-medium text-gray-700 dark:text-gray-300">€ <?= number_format((float)$ad['price'], 2, ',', '.') ?></span></p>
                    </div>
                    <a href="/marketplace/show?id=<?= $ad['id'] ?>" class="w-full py-2 px-4 rounded-lg border border-primary text-primary font-bold text-sm hover:bg-primary hover:text-white transition-colors text-center">
                        Dettagli
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
