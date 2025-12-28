<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestione Team</h1>
    <a href="/users/create" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2 hover:bg-primary/90 transition-colors">
        <span class="material-symbols-outlined text-lg">add</span>
        <span>Nuovo Membro</span>
    </a>
</div>

<div class="bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 font-medium">Nome</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Ruolo</th>
                    <th class="px-6 py-3 font-medium text-right">Azioni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Nessun membro del team trovato.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($u['name']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                <?= htmlspecialchars($u['email']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $roleClasses = match($u['role']) {
                                        'admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
                                        'manager' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                                        'driver' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-300',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                    };
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $roleClasses ?>">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/users/edit?id=<?= $u['id'] ?>" class="text-gray-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <a href="/users/delete?id=<?= $u['id'] ?>" class="text-gray-400 hover:text-red-500 transition-colors" onclick="return confirm('Sicuro di voler eliminare questo utente?')">
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
