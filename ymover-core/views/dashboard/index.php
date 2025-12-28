<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex min-w-72 flex-col gap-1">
        <p class="text-gray-900 dark:text-white text-3xl font-bold leading-tight tracking-tight">Dashboard Analitica</p>
        <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">Bentornato, ecco una panoramica delle performance per il tuo tenant.</p>
    </div>
    <a href="/requests/create" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-wide shadow-sm hover:opacity-90">
        <span class="material-symbols-outlined text-lg"> add </span>
        <span class="truncate">Aggiungi Richiesta</span>
    </a>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="flex flex-col gap-2 rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-gray-700/50">
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Fatturato Accettato</p>
        <p class="text-gray-900 dark:text-white text-2xl font-bold">€ <?= number_format((float)$revenue, 2, ',', '.') ?></p>
    </div>
    <div class="flex flex-col gap-2 rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-gray-700/50">
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Totale Richieste</p>
        <p class="text-gray-900 dark:text-white text-2xl font-bold"><?= array_sum(array_column($requestStats, 'count')) ?></p>
    </div>
    <div class="flex flex-col gap-2 rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-gray-700/50">
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Tasso Conversione</p>
        <?php 
            $total = array_sum(array_column($requestStats, 'count'));
            $confirmed = 0;
            foreach($requestStats as $s) if($s['status'] === 'confirmed' || $s['status'] === 'completed') $confirmed += $s['count'];
            $rate = $total > 0 ? ($confirmed / $total) * 100 : 0;
        ?>
        <p class="text-gray-900 dark:text-white text-2xl font-bold"><?= number_format($rate, 1) ?>%</p>
    </div>
    <div class="flex flex-col gap-2 rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-gray-700/50">
        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Nuove (Last 30d)</p>
        <p class="text-gray-900 dark:text-white text-2xl font-bold"><?= end($trend)['count'] ?? 0 ?></p>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">
    <!-- Chart: Monthly Trend -->
    <div class="lg:col-span-2 flex flex-col gap-4 rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-gray-700/50">
        <h3 class="text-gray-900 dark:text-white text-lg font-bold">Trend Richieste (Ultimi 6 Mesi)</h3>
        <div class="h-[300px]">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
    
    <!-- Chart: Status Distribution -->
    <div class="flex flex-col gap-4 rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-gray-700/50">
        <h3 class="text-gray-900 dark:text-white text-lg font-bold">Stato Richieste</h3>
        <div class="h-[300px]">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 md:grid-cols-3">
    <a href="/requests/create" class="flex flex-col gap-2 rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border-l-4 border-primary hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
        <h5 class="text-primary font-bold">Nuova Richiesta</h5>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Inserisci una nuova richiesta di preventivo.</p>
    </a>
    <a href="/calendar" class="flex flex-col gap-2 rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border-l-4 border-green-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
        <h5 class="text-green-600 font-bold">Calendario</h5>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Gestisci la pianificazione operativa.</p>
    </a>
    <a href="/resources" class="flex flex-col gap-2 rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border-l-4 border-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
        <h5 class="text-blue-400 font-bold">Risorse</h5>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Gestisci veicoli e personale.</p>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($trend, 'month')) ?>,
            datasets: [{
                label: 'Richieste',
                data: <?= json_encode(array_column($trend, 'count')) ?>,
                borderColor: '#2b8cee',
                backgroundColor: 'rgba(43, 140, 238, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(156, 163, 175, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($requestStats, 'status')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($requestStats, 'count')) ?>,
                backgroundColor: ['#2b8cee', '#10b981', '#f59e0b', '#ef4444', '#6b7280', '#06b6d4', '#111827'],
                borderWidth: 0
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            },
            cutout: '70%'
        }
    });
});
</script>
