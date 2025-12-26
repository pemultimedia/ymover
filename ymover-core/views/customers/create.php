<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/customers">Clienti</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuovo Cliente</li>
        </ol>
    </nav>
    <h1>Nuovo Cliente</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="/customers/store" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tipo Cliente</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typePrivate" value="private" checked>
                                <label class="form-check-label" for="typePrivate">Privato</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="typeCompany" value="company">
                                <label class="form-check-label" for="typeCompany">Azienda</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo / Ragione Sociale</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="tax_code" class="form-label">Codice Fiscale / Partita IVA</label>
                        <input type="text" class="form-control" id="tax_code" name="tax_code">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Note</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/customers" class="btn btn-outline-secondary">Annulla</a>
                        <button type="submit" class="btn btn-primary">Salva Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
