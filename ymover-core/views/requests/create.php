<div class="mb-6">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/requests" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-white">
                    Richieste
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 text-lg mx-1">chevron_right</span>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400"><?= __('new_request') ?></span>
                </div>
            </li>
        </ol>
    </nav>
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><?= __('new_request') ?></h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 justify-center">
    <div class="lg:col-span-2 lg:col-start-1 lg:col-end-3">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h5 class="text-lg font-bold text-gray-900 dark:text-white">Dati Iniziali</h5>
            </div>
            <div class="p-6">
                <form action="/requests/store" method="POST" class="space-y-8">
                    <!-- Customer Section -->
                    <div>
                        <h6 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Cliente</h6>
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleziona Cliente Esistente</label>
                                <select name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                                    <option value="">-- Nuovo Cliente --</option>
                                    <?php foreach ($customers as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="relative flex items-center py-2">
                                <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                                <span class="flex-shrink-0 mx-4 text-gray-400 text-xs uppercase">oppure crea nuovo</span>
                                <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                            </div>

                            <div>
                                <input type="text" name="customer_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" placeholder="Nome Nuovo Cliente / Ragione Sociale">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700"></div>

                    <!-- Logistics Section -->
                    <div>
                        <h6 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Logistica (Indirizzi Principali)</h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Origine (Carico)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="material-symbols-outlined text-gray-400">location_on</span>
                                    </div>
                                    <input type="text" name="origin_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" placeholder="Via, Città">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Destinazione (Scarico)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="material-symbols-outlined text-gray-400">flag</span>
                                    </div>
                                    <input type="text" name="destination_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" placeholder="Via, Città">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700"></div>

                    <!-- Request Details -->
                    <div>
                        <h6 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Dettagli Richiesta</h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><?= __('source') ?></label>
                                <select name="source" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                                    <option value="manual">Manuale (Telefono/Email)</option>
                                    <option value="web">Sito Web</option>
                                    <option value="referral">Passaparola</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data Prevista (Opzionale)</label>
                                <input type="date" name="expected_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note Interne</label>
                            <textarea name="internal_notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" placeholder="Note iniziali per l'operativo..."></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4">
                        <button type="submit" class="flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm transition-colors gap-2">
                            <span><?= __('save') ?> e Continua</span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
