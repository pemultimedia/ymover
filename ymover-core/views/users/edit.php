<div class="mb-6">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/users" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary dark:text-gray-400 dark:hover:text-white">
                    Team
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 text-lg mx-1">chevron_right</span>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Modifica Membro</span>
                </div>
            </li>
        </ol>
    </nav>
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Modifica Membro: <?= htmlspecialchars($user['name']) ?></h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6">
                <form action="/users/update" method="POST" class="space-y-6">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome Completo</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" required>
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" required>
                    </div>

                    <div>
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ruolo</label>
                        <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" required>
                            <option value="operative" <?= $user['role'] === 'operative' ? 'selected' : '' ?>>Operativo</option>
                            <option value="driver" <?= $user['role'] === 'driver' ? 'selected' : '' ?>>Autista / Caposquadra</option>
                            <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Amministratore</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="/users" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">Annulla</a>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary/90 shadow-sm transition-colors">Salva Modifiche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
