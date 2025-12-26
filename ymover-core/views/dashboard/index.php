<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold">Dashboard Analitica</h2>
        <p class="text-muted">Panoramica delle performance per il tuo tenant.</p>
    </div>
</div>

<!-- KPI Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h6 class="text-uppercase small">Fatturato Accettato</h6>
                <h2 class="fw-bold mb-0">€ <?= number_format((float)$revenue, 2, ',', '.') ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <h6 class="text-uppercase small">Totale Richieste</h6>
                <h2 class="fw-bold mb-0"><?= array_sum(array_column($requestStats, 'count')) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body">
                <h6 class="text-uppercase small">Tasso Conversione</h6>
                <?php 
                    $total = array_sum(array_column($requestStats, 'count'));
                    $confirmed = 0;
                    foreach($requestStats as $s) if($s['status'] === 'confirmed' || $s['status'] === 'completed') $confirmed += $s['count'];
                    $rate = $total > 0 ? ($confirmed / $total) * 100 : 0;
                ?>
                <h2 class="fw-bold mb-0"><?= number_format($rate, 1) ?>%</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body">
                <h6 class="text-uppercase small">Nuove (Last 30d)</h6>
                <h2 class="fw-bold mb-0"><?= end($trend)['count'] ?? 0 ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart: Monthly Trend -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Trend Richieste (Ultimi 6 Mesi)</div>
            <div class="card-body">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Chart: Status Distribution -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Stato Richieste</div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-primary border-start border-4">
            <div class="card-body">
                <h5 class="card-title text-primary">Nuova Richiesta</h5>
                <p class="card-text">Inserisci una nuova richiesta di preventivo.</p>
                <a href="/requests/create" class="btn btn-primary stretched-link">Crea Richiesta</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-success border-start border-4">
            <div class="card-body">
                <h5 class="card-title text-success">Calendario</h5>
                <p class="card-text">Gestisci la pianificazione operativa.</p>
                <a href="/calendar" class="btn btn-outline-success stretched-link">Vai al Calendario</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-info border-start border-4">
            <div class="card-body">
                <h5 class="card-title text-info">Risorse</h5>
                <p class="card-text">Gestisci veicoli e personale.</p>
                <a href="/resources" class="btn btn-outline-info stretched-link">Gestione Risorse</a>
            </div>
        </div>
    </div>
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
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($requestStats, 'status')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($requestStats, 'count')) ?>,
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d', '#0dcaf0', '#212529']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>

<style>
canvas { max-height: 300px; }
</style>
