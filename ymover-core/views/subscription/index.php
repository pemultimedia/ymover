<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow text-center">
            <div class="card-header bg-primary text-white">
                <h4>Abbonamento YMover</h4>
            </div>
            <div class="card-body">
                <?php if ($tenant['subscription_status'] === 'active'): ?>
                    <h5 class="card-title text-success">Il tuo abbonamento è attivo!</h5>
                    <p class="card-text">Grazie per aver scelto YMover. Il tuo prossimo rinnovo è il <?= htmlspecialchars($tenant['subscription_ends_at'] ?? 'N/A') ?>.</p>
                    <a href="/subscribe/portal" class="btn btn-outline-primary">Gestisci Abbonamento</a>
                <?php elseif ($tenant['subscription_status'] === 'trial'): ?>
                    <h5 class="card-title text-warning">Periodo di Prova</h5>
                    <p class="card-text">Stai utilizzando il periodo di prova gratuito. Scade il <?= htmlspecialchars($tenant['trial_ends_at'] ?? 'N/A') ?>.</p>
                    <form action="/subscribe/checkout" method="POST">
                        <button type="submit" class="btn btn-primary">Attiva Abbonamento Ora (€27/mese)</button>
                    </form>
                <?php else: ?>
                    <h5 class="card-title text-danger">Abbonamento Scaduto o Non Attivo</h5>
                    <p class="card-text">Per continuare ad utilizzare YMover, attiva il tuo abbonamento.</p>
                    <p class="fw-bold">€27 / mese</p>
                    <form action="/subscribe/checkout" method="POST">
                        <button type="submit" class="btn btn-primary btn-lg">Attiva Abbonamento</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
