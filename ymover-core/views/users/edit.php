<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/users">Team</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modifica Membro</li>
        </ol>
    </nav>
    <h1>Modifica Membro: <?= htmlspecialchars($user['name']) ?></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="/users/update" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Ruolo</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="operative" <?= $user['role'] === 'operative' ? 'selected' : '' ?>>Operativo</option>
                            <option value="driver" <?= $user['role'] === 'driver' ? 'selected' : '' ?>>Autista / Caposquadra</option>
                            <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Amministratore</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/users" class="btn btn-outline-secondary">Annulla</a>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
