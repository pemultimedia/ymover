<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YMover CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px; min-height: 100vh;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4">YMover</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="/" class="nav-link text-white">Dashboard</a>
                </li>
                <li>
                    <a href="/customers" class="nav-link text-white">Clienti</a>
                </li>
                <li>
                    <a href="/requests" class="nav-link text-white">Richieste</a>
                </li>
                <hr>
                <li>
                    <a href="/resources" class="nav-link text-white">Risorse</a>
                </li>
                <li>
                    <a href="/users" class="nav-link text-white">Team</a>
                </li>
                <li>
                    <a href="/calendar" class="nav-link text-white">Calendario</a>
                </li>
                <hr>
                <li>
                    <a href="/marketplace" class="nav-link text-white">Marketplace B2B</a>
                </li>
                <li>
                    <a href="/communications" class="nav-link text-white">Comunicazioni</a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
        <div class="flex-grow-1 p-4 bg-light">
            <div class="container-fluid">
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
