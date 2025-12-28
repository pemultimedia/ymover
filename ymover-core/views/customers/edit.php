<div class="mb-6">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/customers" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-white">
                    Clienti
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 text-lg mx-1">chevron_right</span>
                    <a href="/customers/show?id=<?= $customer['id'] ?>" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary md:ml-2 dark:text-gray-400 dark:hover:text-white"><?= htmlspecialchars($customer['name']) ?></a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 text-lg mx-1">chevron_right</span>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Modifica</span>
                </div>
            </li>
        </ol>
    </nav>
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Modifica Cliente</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6">
                <form action="/customers/update" method="POST" class="space-y-6">
                    <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo Cliente</label>
                        <div class="flex gap-4">
                            <div class="flex items-center">
                                <input id="typePrivate" type="radio" value="private" name="type" <?= $customer['type'] === 'private' ? 'checked' : '' ?> class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="typePrivate" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Privato</label>
                            </div>
                            <div class="flex items-center">
                                <input id="typeCompany" type="radio" value="company" name="type" <?= $customer['type'] === 'company' ? 'checked' : '' ?> class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="typeCompany" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Azienda</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome Completo / Ragione Sociale</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($customer['name']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" required>
                    </div>

                    <div>
                        <label for="tax_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codice Fiscale / Partita IVA</label>
                        <input type="text" id="tax_code" name="tax_code" value="<?= htmlspecialchars($customer['tax_code'] ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                    </div>

                    <div>
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note</label>
                        <textarea id="notes" name="notes" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary"><?= htmlspecialchars($customer['notes'] ?? '') ?></textarea>
                    </div>

                    <!-- Dynamic Contacts Section -->
                    <div x-data='{
                        contacts: <?= json_encode($contacts ?? []) ?>,
                        addContact() {
                            this.contacts.push({ type: "phone", value: "", label: "", is_primary: 0 });
                        },
                        removeContact(index) {
                            this.contacts.splice(index, 1);
                        }
                    }'>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Contatti</h3>
                            <button type="button" @click="addContact()" class="text-sm text-primary hover:text-primary-700 font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">add</span> Aggiungi Contatto
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <template x-for="(contact, index) in contacts" :key="index">
                                <div class="flex gap-4 items-start p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 flex-1">
                                        <!-- Type -->
                                        <div class="md:col-span-3">
                                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Tipo</label>
                                            <select :name="`contacts[${index}][type]`" x-model="contact.type" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                <option value="email">Email</option>
                                                <option value="phone">Telefono</option>
                                                <option value="mobile">Cellulare</option>
                                                <option value="whatsapp">WhatsApp</option>
                                                <option value="residence_address">Indirizzo Residenza</option>
                                                <option value="billing_address">Indirizzo Fatturazione</option>
                                                <option value="tax_code">Codice Fiscale</option>
                                                <option value="vat_number">Partita IVA</option>
                                                <option value="sdi_code">Codice SDI</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Value -->
                                        <div class="md:col-span-5">
                                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Valore</label>
                                            <input type="text" :name="`contacts[${index}][value]`" x-model="contact.value" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Inserisci valore..." required>
                                        </div>

                                        <!-- Label -->
                                        <div class="md:col-span-3">
                                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Etichetta</label>
                                            <input type="text" :name="`contacts[${index}][label]`" x-model="contact.label" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Es. Ufficio">
                                        </div>

                                        <!-- Primary -->
                                        <div class="md:col-span-1 flex items-center justify-center pt-6">
                                            <label class="inline-flex items-center cursor-pointer" title="Imposta come principale">
                                                <input type="checkbox" :name="`contacts[${index}][is_primary]`" x-model="contact.is_primary" value="1" class="sr-only peer">
                                                <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <button type="button" @click="removeContact(index)" class="mt-6 text-gray-400 hover:text-red-500 transition-colors">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </template>
                            
                            <div x-show="contacts.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700">
                                Nessun contatto aggiuntivo inserito.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="/customers/show?id=<?= $customer['id'] ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">Annulla</a>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary/90 shadow-sm transition-colors">Salva Modifiche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
