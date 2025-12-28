<div x-data="{ openModal: false }">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em] min-w-72">Calendario Operativo</p>
        <button @click="openModal = true" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
            <span class="material-symbols-outlined text-xl">add</span>
            <span class="truncate">Aggiungi Evento</span>
        </button>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 p-3 border-y border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 mb-6 rounded-xl shadow-sm">
        <div class="flex items-center gap-4">
            <div class="flex gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                    <div class="size-3 rounded-full bg-[#2b8cee]"></div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Lavoro</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="size-3 rounded-full bg-[#10b981]"></div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sopralluogo</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="size-3 rounded-full bg-[#f59e0b]"></div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manutenzione</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 p-4">
        <div id="calendar"></div>
    </div>

    <!-- Event Modal (Tailwind + Alpine.js) -->
    <template x-if="openModal">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div @click.away="openModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white">Nuovo Evento / Impegno</h5>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form action="/calendar/store" method="POST">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Titolo</label>
                            <input type="text" name="title" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" required placeholder="Es. Manutenzione Furgone">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary">
                                <option value="job">Lavoro</option>
                                <option value="inspection">Sopralluogo</option>
                                <option value="maintenance">Manutenzione</option>
                                <option value="unavailable">Non Disponibile</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Inizio</label>
                                <input type="datetime-local" name="start_datetime" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fine</label>
                                <input type="datetime-local" name="end_datetime" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Risorsa Assegnata (Opzionale)</label>
                            <select name="resource_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary focus:border-primary">
                                <option value="">-- Nessuna --</option>
                                <?php foreach ($resources as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                        <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">Annulla</button>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary/90 transition-colors shadow-sm">Salva Evento</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
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
        },
        eventDidMount: function(info) {
            // Apply colors based on type
            const type = info.event.extendedProps.type;
            let color = '#2b8cee'; // Default
            if (type === 'inspection') color = '#10b981';
            if (type === 'maintenance') color = '#f59e0b';
            if (type === 'unavailable') color = '#ef4444';
            
            info.el.style.backgroundColor = color;
            info.el.style.borderColor = color;
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
    border-radius: 4px;
    padding: 2px 4px;
    font-size: 0.85em;
}
.fc-toolbar-title {
    font-size: 1.25em !important;
    font-weight: 700;
}
.fc-button-primary {
    background-color: #2b8cee !important;
    border-color: #2b8cee !important;
}
.fc-button-primary:hover {
    background-color: #1d70c1 !important;
    border-color: #1d70c1 !important;
}
.fc-button-active {
    background-color: #1d70c1 !important;
    border-color: #1d70c1 !important;
}
</style>
