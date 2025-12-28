<!DOCTYPE html>
<html class="light" lang="it">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>YMover CRM</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2b8cee",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 20px;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <?php if (isset($_SESSION['user_id'])): ?>
            <header class="sticky top-0 z-10 flex w-full items-center justify-center border-b border-gray-200/80 bg-background-light/80 dark:border-gray-700/80 dark:bg-background-dark/80 backdrop-blur-sm">
                <div class="flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-3 text-gray-900 dark:text-white">
                            <span class="material-symbols-outlined text-primary text-3xl"> local_shipping </span>
                            <h2 class="text-lg font-bold tracking-tight">YMover CRM</h2>
                        </div>
                        <nav class="hidden items-center gap-6 md:flex">
                            <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary" href="/">Dashboard</a>
                            <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary" href="/requests">Richieste</a>
                            <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary" href="/customers">Clienti</a>
                            <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary" href="/calendar">Calendario</a>
                            <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary" href="/resources">Risorse</a>
                            <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary" href="/users">Team</a>
                            <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary" href="/marketplace">Marketplace</a>
                            <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary" href="/storage">Gestione Depositi</a>
                        </nav>
                    </div>
                    <div class="flex flex-shrink-0 items-center justify-end gap-4">
                        <button class="flex h-9 w-9 cursor-pointer items-center justify-center overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                            <span class="material-symbols-outlined text-xl"> notifications </span>
                        </button>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 cursor-pointer focus:outline-none">
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://lh3.googleusercontent.com/a/default-user=s96-c");'></div>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Profilo</a>
                                <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">Esci</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <?php endif; ?>
            <main class="flex flex-1 justify-center py-8">
                <div class="layout-content-container flex flex-col w-full max-w-7xl flex-1 px-4 sm:px-6 lg:px-8">
                    <?= $content ?? '' ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
