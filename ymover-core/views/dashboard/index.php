<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold">Dashboard</h2>
        <p class="text-muted">Benvenuto in YMover, <?= htmlspecialchars($_SESSION['user_id'] ?? 'Utente') ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-primary border-start border-4">
            <div class="card-body">
                <h5 class="card-title text-primary">Nuova Richiesta</h5>
                <p class="card-text">Inserisci una nuova richiesta di preventivo per un cliente.</p>
                <a href="/requests/create" class="btn btn-primary stretched-link">Crea Richiesta</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-success border-start border-4">
            <div class="card-body">
                <h5 class="card-title text-success">Richieste Attive</h5>
                <p class="card-text">Visualizza e gestisci le richieste in corso.</p>
                <a href="/requests" class="btn btn-outline-success stretched-link">Vai alle Richieste</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border-info border-start border-4">
            <div class="card-body">
                <h5 class="card-title text-info">Abbonamento</h5>
                <p class="card-text">Gestisci il tuo piano e i pagamenti.</p>
                <a href="/subscribe" class="btn btn-outline-info stretched-link">Il mio Abbonamento</a>
            </div>
        </div>
    </div>
</div>
