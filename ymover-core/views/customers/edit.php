<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/customers">Clienti</a></li>
            <li class="breadcrumb-item"><a href="/customers/show?id=<?= $customer['id'] ?>"><?= htmlspecialchars($customer['name']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Modifica</li>
        </ol>
    </nav>
    <h1>Modifica Cliente</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="/customers/update" method="POST">
                    <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Tipo Cliente</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typePrivate" value="private" <?= $customer['type'] === 'private' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="typePrivate">Privato</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeCompany" value="company" <?= $customer['type'] === 'company' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="typeCompany">Azienda</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo / Ragione Sociale</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="tax_code" class="form-label">Codice Fiscale / Partita IVA</label>
                        <input type="text" class="form-control" id="tax_code" name="tax_code" value="<?= htmlspecialchars($customer['tax_code'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Note</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($customer['notes'] ?? '') ?></textarea>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/customers/show?id=<?= $customer['id'] ?>" class="btn btn-outline-secondary">Annulla</a>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
