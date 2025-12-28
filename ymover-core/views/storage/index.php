<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Contratti di Deposito</h1>
        <p class="text-gray-500 dark:text-gray-400">Gestisci i contratti di stoccaggio e i movimenti.</p>
    </div>
    <a href="/storage/create" class="px-4 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-colors shadow-sm flex items-center">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Nuovo Contratto
    </a>
</div>

<div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Cliente</th>
                    <th scope="col" class="px-6 py-3">Magazzino</th>
                    <th scope="col" class="px-6 py-3">Inizio</th>
                    <th scope="col" class="px-6 py-3">Stato</th>
                    <th scope="col" class="px-6 py-3">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($contracts)): ?>
                    <?php foreach ($contracts as $contract): ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                <?= htmlspecialchars($contract['customer_name']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($contract['warehouse_name']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= date('d/m/Y', strtotime($contract['start_date'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    <?= $contract['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= ucfirst($contract['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="/storage/show?id=<?= $contract['id'] ?>" class="font-medium text-primary hover:underline">Dettagli</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">Nessun contratto trovato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
