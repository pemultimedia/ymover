<!DOCTYPE html>
<html lang="it" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheda Richiesta #<?= $request['id'] ?> | YMover</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' },
                        slate: { 850: '#1e293b' }
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-rounded { font-size: 20px; vertical-align: middle; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased h-screen flex flex-col overflow-hidden" x-data="requestPage">

    <!-- ================= HEADER PRINCIPALE ================= -->
    <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 shrink-0 z-20">
        <div class="flex items-center gap-4">
            <a href="/requests" class="text-gray-500 hover:text-primary-600 transition"><span class="material-symbols-rounded text-2xl">arrow_back</span></a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-bold text-slate-900">Richiesta #<?= $request['id'] ?></h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200"><?= ucfirst($request['status']) ?></span>
                </div>
                <p class="text-xs text-gray-500">Creata il <?= date('d/m/Y', strtotime($request['created_at'])) ?> da Web</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex bg-gray-100 rounded-lg p-1">
                <button class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-slate-900 hover:bg-white rounded-md transition shadow-sm" title="Invia Email"><span class="material-symbols-rounded">mail</span></button>
                <button class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-slate-900 hover:bg-white rounded-md transition" title="Chiama"><span class="material-symbols-rounded">call</span></button>
                <button class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-slate-900 hover:bg-white rounded-md transition" title="WhatsApp"><span class="material-symbols-rounded">chat</span></button>
            </div>
            
            <div class="h-6 w-px bg-gray-300 mx-1"></div>

            <button class="btn-secondary px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm">
                <span class="material-symbols-rounded mr-1">print</span> Anteprima
            </button>
            <button class="btn-primary px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 shadow-sm flex items-center">
                <span class="material-symbols-rounded mr-1">send</span> Invia Preventivo
            </button>
        </div>
    </header>

    <!-- ================= LAYOUT PRINCIPALE (GRID) ================= -->
    <main class="flex-1 flex overflow-hidden">
        
        <!-- COLONNA SINISTRA: OPERATIVITÀ (Scrollabile) -->
        <div class="flex-1 overflow-y-auto p-6 scrollbar-hide">
            <div class="max-w-5xl mx-auto space-y-6">

                <!-- 1. CARD CLIENTE COMPATTA -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex justify-between items-start">
                    <div class="flex gap-4">
                        <div class="h-12 w-12 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-xl font-bold">
                            <?= strtoupper(substr($customer['name'], 0, 2)) ?>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($customer['name']) ?></h2>
                            <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-base">mail</span> <?= htmlspecialchars($customer['email'] ?? '-') ?></span>
                                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-base">phone</span> <?= htmlspecialchars($customer['phone'] ?? '-') ?></span>
                                <span class="flex items-center gap-1 text-green-600 bg-green-50 px-2 py-0.5 rounded"><span class="material-symbols-rounded text-base">check_circle</span> Cliente Verificato</span>
                            </div>
                        </div>
                    </div>
                    <a href="/customers/edit?id=<?= $customer['id'] ?>" class="text-primary-600 text-sm font-medium hover:underline">Modifica Anagrafica</a>
                </div>

                <!-- 2. NAVIGAZIONE TAB PRINCIPALI -->
                <div class="border-b border-gray-200">
                    <nav class="flex gap-6" aria-label="Tabs">
                        <button @click="activeTab = 'logistics'" :class="activeTab === 'logistics' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                            <span class="material-symbols-rounded">map</span> Logistica & Stop
                        </button>
                        <button @click="activeTab = 'inventory'" :class="activeTab === 'inventory' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                            <span class="material-symbols-rounded">inventory_2</span> Inventario & Beni
                            <?php 
                                $totalVol = 0;
                                foreach($inventoryVersions as $v) { if($v['is_selected']) $totalVol = $v['total_volume']; }
                            ?>
                            <span class="bg-primary-100 text-primary-700 py-0.5 px-2 rounded-full text-xs"><?= number_format($totalVol, 2) ?> m³</span>
                        </button>
                        <button @click="activeTab = 'planning'" :class="activeTab === 'planning' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                            <span class="material-symbols-rounded">calendar_month</span> Pianificazione
                        </button>
                    </nav>
                </div>

                <!-- CONTENUTO TAB: LOGISTICA (Timeline) -->
                <div x-show="activeTab === 'logistics'" x-cloak class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-slate-800">Itinerario del Trasloco</h3>
                            <button class="text-sm text-primary-600 font-medium flex items-center gap-1"><span class="material-symbols-rounded">add_location</span> Aggiungi Stop</button>
                        </div>
                        
                        <!-- Timeline -->
                        <div class="relative pl-4 border-l-2 border-gray-200 space-y-8">
                            <?php foreach ($stops as $index => $stop): ?>
                                <?php 
                                    $isFirst = $index === 0;
                                    $isLast = $index === count($stops) - 1;
                                    $isWarehouse = !empty($stop['warehouse_id']);
                                    
                                    $dotColor = 'bg-gray-400';
                                    if ($isFirst) $dotColor = 'bg-green-500';
                                    elseif ($isLast) $dotColor = 'bg-red-500';
                                    elseif ($isWarehouse) $dotColor = 'bg-orange-500';

                                    $cardBg = $isWarehouse ? 'bg-orange-50 border-orange-200' : 'bg-gray-50 border-gray-200';
                                    $titleColor = $isWarehouse ? 'text-orange-600' : ($isFirst ? 'text-green-600' : ($isLast ? 'text-red-600' : 'text-gray-600'));
                                    $titleText = $isWarehouse ? 'Sosta in Deposito' : ($isFirst ? 'Origine (Carico)' : ($isLast ? 'Destinazione (Scarico)' : 'Stop Intermedio'));
                                ?>
                                
                                <div class="relative pl-6">
                                    <div class="absolute -left-[9px] top-0 h-4 w-4 rounded-full <?= $dotColor ?> border-2 border-white shadow"></div>
                                    <div class="<?= $cardBg ?> rounded-lg p-4 border hover:border-primary-300 transition cursor-pointer group">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="text-xs font-bold <?= $titleColor ?> uppercase tracking-wide flex items-center gap-1">
                                                    <?php if($isWarehouse): ?><span class="material-symbols-rounded text-sm">warehouse</span><?php endif; ?>
                                                    <?= $titleText ?>
                                                </span>
                                                <p class="font-bold text-slate-900 mt-1"><?= htmlspecialchars($stop['address_full']) ?></p>
                                                <div class="flex gap-3 mt-2 text-sm text-gray-600">
                                                    <span class="flex items-center gap-1" title="Piano"><span class="material-symbols-rounded text-base">stairs</span> <?= $stop['floor'] ?>° Piano</span>
                                                    <span class="flex items-center gap-1" title="Ascensore">
                                                        <span class="material-symbols-rounded text-base <?= $stop['elevator_status'] === 'yes' ? 'text-green-600' : 'text-red-500' ?>">elevator</span> 
                                                        <?= $stop['elevator_status'] === 'yes' ? 'Sì' : 'No' ?>
                                                    </span>
                                                    <span class="flex items-center gap-1" title="Distanza Parcheggio"><span class="material-symbols-rounded text-base">local_parking</span> <?= $stop['distance_from_parking'] ?>m</span>
                                                </div>
                                            </div>
                                            <button class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-primary-600"><span class="material-symbols-rounded">edit</span></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between text-sm text-gray-500">
                            <span>Totale Distanza: <strong>- km</strong> (Calcolo automatico WIP)</span>
                            <span>Tempo Stimato Viaggio: <strong>-</strong></span>
                        </div>
                    </div>
                </div>

                <!-- CONTENUTO TAB: INVENTARIO (Core) -->
                <div x-show="activeTab === 'inventory'" x-cloak class="space-y-4">
                    
                    <!-- Toolbar Inventario -->
                    <div class="flex justify-between items-center bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-3">
                            <label class="text-sm font-medium text-gray-600">Versione:</label>
                            <select class="form-select text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 py-1.5">
                                <?php foreach ($inventoryVersions as $version): ?>
                                    <option value="<?= $version['id'] ?>" <?= $version['is_selected'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($version['name']) ?> (<?= number_format($version['total_volume'], 2) ?> m³)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="text-gray-400 hover:text-primary-600" title="Clona Versione"><span class="material-symbols-rounded">content_copy</span></button>
                        </div>
                        <div class="flex gap-2">
                            <button @click="openBlockModal()" class="btn-secondary px-3 py-1.5 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50 flex items-center gap-1">
                                <span class="material-symbols-rounded text-base">add_box</span> Nuovo Blocco
                            </button>
                            <button @click="openItemModal(null)" class="btn-primary px-3 py-1.5 text-sm bg-primary-600 text-white rounded-md hover:bg-primary-700 flex items-center gap-1">
                                <span class="material-symbols-rounded text-base">add</span> Aggiungi Elementi
                            </button>
                        </div>
                    </div>

                    <?php 
                        $currentVersion = null;
                        foreach($inventoryVersions as $v) { if($v['is_selected']) $currentVersion = $v; }
                    ?>

                    <?php if ($currentVersion): ?>
                        <?php foreach ($currentVersion['blocks'] as $block): ?>
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ open: true }">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center cursor-pointer" @click="open = !open">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-rounded text-gray-400 transform transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                                        <h4 class="font-bold text-slate-800"><?= htmlspecialchars($block['name']) ?></h4>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="text-sm font-medium text-gray-600"><?= number_format($block['volume'], 2) ?> m³</div>
                                        <button @click.stop="openItemModal(<?= $block['id'] ?>)" class="text-primary-600 hover:text-primary-700 p-1" title="Aggiungi Elemento a questo blocco">
                                            <span class="material-symbols-rounded">add_circle</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <div x-show="open" class="divide-y divide-gray-100">
                                    <?php foreach ($block['items'] as $item): ?>
                                        <div class="p-3 hover:bg-blue-50 flex items-center justify-between group transition">
                                            <div class="flex items-center gap-4">
                                                <div class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center text-gray-500">
                                                    <span class="material-symbols-rounded">chair</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-slate-900"><?= htmlspecialchars($item['name']) ?></p>
                                                    <p class="text-xs text-gray-500">
                                                        <?= $item['width'] ?> x <?= $item['height'] ?> x <?= $item['depth'] ?> cm • Qty: <?= $item['quantity'] ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-6">
                                                <div class="flex gap-1">
                                                    <?php if ($item['is_disassembly']): ?>
                                                        <span class="text-[10px] uppercase font-bold text-gray-400 border border-gray-200 px-1 rounded" title="Smontaggio">SM</span>
                                                    <?php endif; ?>
                                                    <?php if ($item['is_packing']): ?>
                                                        <span class="text-[10px] uppercase font-bold text-gray-400 border border-gray-200 px-1 rounded" title="Imballo">IM</span>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="font-mono text-sm font-bold text-slate-700">
                                                    <?= number_format(($item['width'] * $item['height'] * $item['depth'] * $item['quantity']) / 1000000, 2) ?> m³
                                                </span>
                                                <div class="opacity-0 group-hover:opacity-100 flex gap-1">
                                                    <button class="text-gray-400 hover:text-blue-600"><span class="material-symbols-rounded">edit</span></button>
                                                    <button class="text-gray-400 hover:text-red-600"><span class="material-symbols-rounded">delete</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

				<!-- CONTENUTO TAB: PIANIFICAZIONE -->
				<div x-show="activeTab === 'planning'" x-cloak class="space-y-6">

					<!-- 1. STATO ATTUALE E DATE -->
					<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
						
						<!-- Card Data Principale -->
						<div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative overflow-hidden">
							<div class="absolute top-0 right-0 p-4 opacity-10">
								<span class="material-symbols-rounded text-9xl text-primary-600">calendar_clock</span>
							</div>
							
							<div class="relative z-10">
								<h3 class="font-bold text-slate-800 mb-1">Schedulazione Attuale</h3>
								<p class="text-sm text-gray-500 mb-6">Definisci quando verrà eseguito il servizio.</p>

								<div class="flex items-center gap-6">
									<div class="bg-primary-50 border border-primary-100 rounded-lg p-4 flex-1">
										<span class="text-xs font-bold text-primary-600 uppercase tracking-wide">Inizio Lavori (Carico)</span>
										<div class="flex items-center gap-2 mt-1">
											<span class="text-2xl font-bold text-slate-900">--/--/----</span>
											<span class="text-sm font-medium text-gray-600">ore --:--</span>
										</div>
									</div>
									<span class="material-symbols-rounded text-gray-300 text-2xl">arrow_forward</span>
									<div class="bg-primary-50 border border-primary-100 rounded-lg p-4 flex-1">
										<span class="text-xs font-bold text-primary-600 uppercase tracking-wide">Fine Lavori (Scarico)</span>
										<div class="flex items-center gap-2 mt-1">
											<span class="text-2xl font-bold text-slate-900">--/--/----</span>
											<span class="text-sm font-medium text-gray-600">ore --:--</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Card Smart Suggestions (AI Geospaziale) -->
						<div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl border border-indigo-100 shadow-sm p-5">
							<div class="flex items-center gap-2 mb-4">
								<span class="material-symbols-rounded text-indigo-600">auto_awesome</span>
								<h3 class="font-bold text-indigo-900">Suggerimenti Smart</h3>
							</div>
							
							<div class="space-y-3">
								<!-- Opzione 1: Geo-Link -->
								<div class="bg-white p-3 rounded-lg border border-indigo-100 shadow-sm hover:border-indigo-300 cursor-pointer transition group">
									<div class="flex justify-between items-start">
										<span class="text-xs font-bold bg-green-100 text-green-700 px-2 py-0.5 rounded">Ottimizzato</span>
										<span class="text-xs text-gray-400">Risparmio 15%</span>
									</div>
									<p class="text-sm font-bold text-slate-800 mt-1">Nessun suggerimento</p>
									<p class="text-xs text-gray-600 mt-1 leading-relaxed">
										Non ci sono camion liberi nelle vicinanze per questa tratta al momento.
									</p>
								</div>
							</div>
						</div>
					</div>

					<!-- 2. GESTIONE RISORSE (MEZZI E UOMINI) -->
					<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
						<div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
							<h3 class="font-bold text-slate-800 flex items-center gap-2">
								<span class="material-symbols-rounded text-gray-500">groups</span> Risorse Assegnate
							</h3>
							<div class="flex gap-2">
								<button class="btn-secondary px-3 py-1.5 text-xs bg-white border border-gray-300 rounded-md hover:bg-gray-50 flex items-center gap-1">
									<span class="material-symbols-rounded text-sm">add</span> Aggiungi Operaio
								</button>
								<button class="btn-secondary px-3 py-1.5 text-xs bg-white border border-gray-300 rounded-md hover:bg-gray-50 flex items-center gap-1">
									<span class="material-symbols-rounded text-sm">local_shipping</span> Aggiungi Mezzo
								</button>
							</div>
						</div>

						<div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
							
							<!-- Colonna Mezzi -->
							<div>
								<h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Automezzi & Attrezzature</h4>
								<div class="space-y-3">
                                    <?php foreach ($resources as $resource): ?>
                                        <?php if ($resource['type'] === 'vehicle'): ?>
                                            <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-10 w-10 rounded bg-blue-50 flex items-center justify-center text-blue-600">
                                                        <span class="material-symbols-rounded">local_shipping</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-900"><?= htmlspecialchars($resource['name']) ?></p>
                                                        <p class="text-xs text-gray-500"><span class="text-green-600">Disponibile</span></p>
                                                    </div>
                                                </div>
                                                <button class="text-gray-400 hover:text-red-500"><span class="material-symbols-rounded">close</span></button>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
								</div>
							</div>

							<!-- Colonna Personale -->
							<div>
								<h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Squadra Operativa</h4>
								<div class="space-y-3">
                                    <?php foreach ($resources as $resource): ?>
                                        <?php if ($resource['type'] === 'personnel'): ?>
                                            <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs">
                                                        <?= strtoupper(substr($resource['name'], 0, 2)) ?>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-900"><?= htmlspecialchars($resource['name']) ?></p>
                                                        <p class="text-xs text-gray-500">Operaio</p>
                                                    </div>
                                                </div>
                                                <button class="text-gray-400 hover:text-red-500"><span class="material-symbols-rounded">close</span></button>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
								</div>
							</div>

						</div>
					</div>

				</div>

            </div>
        </div>

        <!-- COLONNA DESTRA: STICKY SIDEBAR (Contesto & Storico) -->
        <aside class="w-96 bg-white border-l border-gray-200 flex flex-col shrink-0 z-10 shadow-lg">
            
            <!-- 1. RIEPILOGO ECONOMICO (Sempre visibile) -->
            <div class="p-5 bg-slate-50 border-b border-gray-200">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Stima Costi</h3>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Volume (<?= number_format($totalVol, 2) ?> m³)</span>
                        <span class="font-medium">€ -</span>
                    </div>
                </div>
                
                <div class="pt-3 border-t border-gray-200 flex justify-between items-end">
                    <span class="text-sm font-medium text-gray-900">Totale (IVA esc.)</span>
                    <span class="text-2xl font-bold text-primary-700">€ -</span>
                </div>
            </div>

            <!-- 2. TAB SIDEBAR -->
            <div class="flex border-b border-gray-200">
                <button @click="sidebarTab = 'notes'" :class="sidebarTab === 'notes' ? 'border-primary-600 text-primary-600 bg-primary-50' : 'border-transparent text-gray-500 hover:bg-gray-50'" class="flex-1 py-3 text-sm font-medium border-b-2 text-center">Note & Storico</button>
                <button @click="sidebarTab = 'files'" :class="sidebarTab === 'files' ? 'border-primary-600 text-primary-600 bg-primary-50' : 'border-transparent text-gray-500 hover:bg-gray-50'" class="flex-1 py-3 text-sm font-medium border-b-2 text-center">File</button>
            </div>

            <!-- 3. CONTENUTO SIDEBAR (Scrollabile) -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50">
                
                <!-- TAB NOTE (Chat Style) -->
                <div x-show="sidebarTab === 'notes'" class="space-y-4">
                    <!-- Input Nota -->
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <textarea x-ref="noteInput" class="w-full text-sm border-0 focus:ring-0 p-0 resize-none" rows="2" placeholder="Scrivi una nota interna..."></textarea>
                        
                        <!-- Preview Allegati Nota -->
                        <div x-show="selectedNoteFiles.length > 0" class="mt-2 space-y-1">
                            <template x-for="(file, index) in selectedNoteFiles" :key="index">
                                <div class="flex items-center justify-between bg-gray-50 px-2 py-1 rounded text-xs border border-gray-100">
                                    <span class="truncate text-gray-600" x-text="file.name"></span>
                                    <button @click="removeNoteFile(index)" class="text-gray-400 hover:text-red-500"><span class="material-symbols-rounded text-sm">close</span></button>
                                </div>
                            </template>
                        </div>

                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
                            <div class="flex gap-2">
                                <input type="file" x-ref="noteFiles" @change="handleNoteFilesChange" multiple class="hidden">
                                <button @click="$refs.noteFiles.click()" class="text-gray-400 hover:text-gray-600" title="Allega file"><span class="material-symbols-rounded text-lg">attach_file</span></button>
                            </div>
                            <button @click="saveNote()" class="bg-slate-800 text-white text-xs font-bold px-3 py-1.5 rounded hover:bg-slate-700">Salva</button>
                        </div>
                    </div>

                    <!-- Timeline Eventi -->
                    <div class="relative pl-4 border-l border-gray-200 space-y-6">
                        <?php foreach ($notes as $note): ?>
                            <div class="relative pl-4">
                                <div class="absolute -left-[5px] top-1 h-2.5 w-2.5 rounded-full bg-blue-500"></div>
                                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm text-sm">
                                    <p class="text-gray-800"><strong><?= htmlspecialchars((string)($note['author_name'] ?? 'Sistema')) ?></strong>: <?= htmlspecialchars((string)($note['text'] ?? '')) ?></p>
                                    
                                    <?php if (!empty($note['attachments'])): ?>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <?php foreach ($note['attachments'] as $attach): ?>
                                                <a href="/<?= htmlspecialchars($attach['file_path']) ?>" target="_blank" class="flex items-center gap-1 bg-gray-50 border border-gray-200 rounded px-2 py-1 text-xs text-primary-600 hover:bg-primary-50 transition">
                                                    <span class="material-symbols-rounded text-sm">description</span>
                                                    <?= htmlspecialchars($attach['filename']) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <p class="text-xs text-gray-400 mt-1"><?= date('d/m/Y H:i', strtotime($note['created_at'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                </div>

            </div>
        </aside>

    </main>
            <div x-show="showBlockModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="/api/inventory/block/create" method="POST">
                    <input type="hidden" name="version_id" :value="currentVersionId">
                    <input type="hidden" name="redirect_url" value="/requests/show?id=<?= $request['id'] ?>">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Nuovo Blocco Inventario</h3>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Nome Blocco</label>
                            <input type="text" name="name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Es. Cucina, Salotto" required>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Crea</button>
                        <button type="button" @click="showBlockModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annulla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Aggiungi Elemento -->
    <div x-show="showItemModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showItemModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showItemModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showItemModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="/api/inventory/item/add" method="POST">
                    <input type="hidden" name="block_id" :value="currentBlockId">
                    <input type="hidden" name="redirect_url" value="/requests/show?id=<?= $request['id'] ?>">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Aggiungi Elemento</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Descrizione</label>
                                <input type="text" name="name" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Es. Divano" required>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">L (cm)</label>
                                    <input type="number" name="width" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">H (cm)</label>
                                    <input type="number" name="height" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">P (cm)</label>
                                    <input type="number" name="depth" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quantità</label>
                                <input type="number" name="quantity" value="1" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_disassembly" value="1" class="form-checkbox h-4 w-4 text-primary-600">
                                    <span class="ml-2 text-sm text-gray-700">Smontaggio</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_packing" value="1" class="form-checkbox h-4 w-4 text-primary-600">
                                    <span class="ml-2 text-sm text-gray-700">Imballo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Aggiungi</button>
                        <button type="button" @click="showItemModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annulla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Carica File -->
    <div x-show="showFileModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showFileModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showFileModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showFileModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="/requests/upload-file" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Carica File</h3>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Seleziona File</label>
                            <input type="file" name="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" required>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Carica</button>
                        <button type="button" @click="showFileModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Annulla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('requestPage', () => ({
                activeTab: 'inventory',
                sidebarTab: 'notes',
                showBlockModal: false,
                showItemModal: false,
                showFileModal: false,
                currentVersionId: <?= $currentVersion['id'] ?? 'null' ?>,
                currentBlockId: null,
                selectedNoteFiles: [],

                openBlockModal() {
                    this.showBlockModal = true;
                },

                openItemModal(blockId) {
                    this.currentBlockId = blockId;
                    this.showItemModal = true;
                },

                openFileModal() {
                    this.showFileModal = true;
                },

                handleNoteFilesChange(event) {
                    const files = Array.from(event.target.files);
                    this.selectedNoteFiles = [...this.selectedNoteFiles, ...files];
                },

                removeNoteFile(index) {
                    this.selectedNoteFiles.splice(index, 1);
                },

                saveNote() {
                    const noteText = this.$refs.noteInput.value;
                    if (!noteText && this.selectedNoteFiles.length === 0) return;

                    const formData = new FormData();
                    formData.append('request_id', '<?= $request['id'] ?>');
                    formData.append('text', noteText);
                    
                    this.selectedNoteFiles.forEach((file, i) => {
                        formData.append('files[]', file);
                    });

                    fetch('/requests/add-note', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.reload();
                        }
                    });
                }
            }))
        })
    </script>

</body>
</html>
