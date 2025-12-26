<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preventivo #<?= $quote['id'] ?> - YMover</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }
        .quote-container { max-width: 800px; margin: 50px auto; background: white; padding: 40px; border-radius: 15px; shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .brand { font-size: 2rem; font-weight: bold; color: #0d6efd; margin-bottom: 30px; }
        .price-box { background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; margin: 30px 0; }
        .price-value { font-size: 2.5rem; font-weight: bold; color: #198754; }
    </style>
</head>
<body>

<div class="container">
    <div class="quote-container shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="brand">YMover</div>
            <div class="text-end">
                <h4 class="mb-0">Preventivo #<?= $quote['id'] ?></h4>
                <p class="text-muted small">Data: <?= date('d/m/Y', strtotime($quote['created_at'])) ?></p>
            </div>
        </div>

        <hr>

        <?php if (isset($_GET['accepted'])): ?>
            <div class="alert alert-success py-4 text-center">
                <h4 class="alert-heading">Grazie!</h4>
                <p class="mb-0">Il preventivo è stato accettato con successo. Verrai contattato a breve dal nostro team operativo.</p>
            </div>
        <?php else: ?>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h6>Dettagli Servizio</h6>
                    <p class="small">Trasloco / Trasporto gestito da YMover.<br>
                    Riferimento Richiesta: #<?= $request['id'] ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6>Scadenza Offerta</h6>
                    <p class="small text-danger fw-bold"><?= $quote['expiration_date'] ? date('d/m/Y', strtotime($quote['expiration_date'])) : 'Nessuna' ?></p>
                </div>
            </div>

            <div class="price-box">
                <div class="text-muted mb-2">Totale Preventivato (IVA Inclusa)</div>
                <div class="price-value">€ <?= number_format((float)$quote['total_amount'], 2, ',', '.') ?></div>
            </div>

            <div class="mt-5">
                <h6>Termini e Condizioni</h6>
                <p class="text-muted small">L'accettazione del presente preventivo implica l'approvazione dei termini di servizio di YMover. Il prezzo finale potrebbe variare in caso di modifiche sostanziali all'inventario o alle condizioni di accesso non dichiarate.</p>
            </div>

            <?php if ($quote['status'] === 'draft' || $quote['status'] === 'sent'): ?>
            <div class="d-grid gap-2 mt-5">
                <form action="/quotes/accept" method="POST">
                    <input type="hidden" name="id" value="<?= $quote['id'] ?>">
                    <button type="submit" class="btn btn-success btn-lg w-100 py-3 fw-bold" onclick="return confirm('Confermi l\'accettazione del preventivo?')">Accetta e Conferma Prenotazione</button>
                </form>
            </div>
            <?php else: ?>
                <div class="alert alert-info text-center mt-5">
                    Questo preventivo è in stato: <strong><?= ucfirst($quote['status']) ?></strong>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <div class="text-center mt-5 pt-4 border-top text-muted small">
            YMover S.r.l. - Via Roma 1, Milano - www.ymover.com
        </div>
    </div>
</div>

</body>
</html>
