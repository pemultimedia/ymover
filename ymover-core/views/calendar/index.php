<div class="mb-4 d-flex justify-content-between align-items-center">
    <h1>Calendario Operativo</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal">+ Nuovo Evento</button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuovo Evento / Impegno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/calendar/store" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Titolo</label>
                        <input type="text" name="title" class="form-control" required placeholder="Es. Manutenzione Furgone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="type" class="form-select">
                            <option value="job">Lavoro</option>
                            <option value="inspection">Sopralluogo</option>
                            <option value="maintenance">Manutenzione</option>
                            <option value="unavailable">Non Disponibile</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Inizio</label>
                            <input type="datetime-local" name="start_datetime" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fine</label>
                            <input type="datetime-local" name="end_datetime" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Risorsa Assegnata (Opzionale)</label>
                        <select name="resource_id" class="form-select">
                            <option value="">-- Nessuna --</option>
                            <?php foreach ($resources as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Salva Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- FullCalendar Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'it',
        events: '/calendar/events',
        eventClick: function(info) {
            if (info.event.extendedProps.request_id) {
                window.location.href = '/requests/show?id=' + info.event.extendedProps.request_id;
            } else {
                if (confirm('Eliminare questo evento?')) {
                    window.location.href = '/calendar/delete?id=' + info.event.id;
                }
            }
        }
    });
    calendar.render();
});
</script>

<style>
#calendar {
    min-height: 600px;
}
.fc-event {
    cursor: pointer;
}
</style>
