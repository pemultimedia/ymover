<!DOCTYPE html>
<html lang="it" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | YMover</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' },
                        slate: { 850: '#1e293b' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary-100 text-primary-600 mb-4">
                    <span class="material-symbols-rounded text-2xl">move_location</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Benvenuto in YMover</h1>
                <p class="text-gray-500 mt-2">Accedi per gestire i tuoi traslochi</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-symbols-rounded text-red-500">error</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <?= $_SESSION['error'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="/login" method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-rounded text-gray-400">mail</span>
                        </div>
                        <input type="email" name="email" id="email" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="nome@azienda.com" required autofocus>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-rounded text-gray-400">lock</span>
                        </div>
                        <input type="password" name="password" id="password" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">Ricordami</label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-primary-600 hover:text-primary-500">Password dimenticata?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        Accedi
                    </button>
                </div>
            </form>
        </div>
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">&copy; <?= date('Y') ?> YMover Inc. Tutti i diritti riservati.</p>
        </div>
    </div>

</body>
</html>
