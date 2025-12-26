<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/users">Team</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuovo Membro</li>
        </ol>
    </nav>
    <h1>Aggiungi Membro al Team</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="/users/store" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Iniziale</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Ruolo</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="operative">Operativo</option>
                            <option value="driver">Autista / Caposquadra</option>
                            <option value="manager">Manager</option>
                            <option value="admin">Amministratore</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/users" class="btn btn-outline-secondary">Annulla</a>
                        <button type="submit" class="btn btn-primary">Salva Membro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
