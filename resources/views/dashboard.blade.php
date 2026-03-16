<x-app-layout>
    <style>
        /* 全テキストを極太に固定 */
        * { font-weight: 900 !important; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* 今日のスタイル：目に優しい淡い赤 */
        .is-today {
            background-color: #fef2f2 !important; /* さらに淡い赤 */
            border: 3px solid #f87171 !important; /* 柔らかい赤の枠線 */
            color: #ef4444 !important;
        }

        /* 選択された時のスタイル：青 */
        .is-selected {
            background-color: #2563eb !important; /* 濃いめの青で文字を白く */
            color: white !important;
            border-color: #1e40af !important;
            transform: scale(1.1);
        }

        /* 今日かつ選択されている時：青背景に赤枠を維持して「今日」だとわかるように */
        .is-today.is-selected {
            border-color: #ef4444 !important; 
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.4); /* 外側にうっすら赤の後光 */
        }
    </style>

    <div class="px-4 py-3 flex justify-between items-center">
        <div>
            <p class="text-sm font-black text-blue-600 uppercase">Agenda de</p>
            <h2 class="text-3xl font-black text-gray-900">{{ Auth::user()->name }}</h2>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs font-black text-gray-400 border-2 border-gray-200 px-3 py-1 rounded-xl">SAIR</button>
        </form>
    </div>

    <div class="py-2" x-data="appointmentManager()" x-init="fetchAppointments()">
        
        <template x-if="showModal">
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
                <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-sm text-center">
                    <h3 class="text-2xl font-black text-gray-900 mb-6">Apagar selecionados?</h3>
                    <div class="flex flex-col space-y-3">
                        <button @click="confirmDelete()" class="w-full bg-red-600 text-white py-5 rounded-2xl text-xl">SIM, APAGAR</button>
                        <button @click="showModal = false" class="w-full bg-gray-100 text-gray-700 py-5 rounded-2xl text-xl">CANCELAR</button>
                    </div>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-2">
            
            <div class="bg-white p-5 rounded-3xl shadow-xl mb-6 border-b-8 border-blue-600">
                <form @submit.prevent="addAppointment" class="flex flex-col gap-3">
                    @csrf
                    <input type="text" x-model="newApp.client_name" placeholder="Nome do Cliente" class="rounded-2xl border-4 border-gray-100 p-4 text-xl w-full" required>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   x-model="newApp.start_time" 
                                   @input="newApp.start_time = autoFormatTime($event.target.value)"
                                   inputmode="numeric" 
                                   maxlength="5"
                                   placeholder="14:00" 
                                   class="rounded-2xl border-4 border-gray-100 pl-12 p-4 text-xl w-full font-black focus:border-blue-600 outline-none transition-all" 
                                   required>
                        </div>
                        
                        <select x-model="newApp.duration" class="rounded-2xl border-4 border-gray-100 p-4 text-xl font-black focus:border-blue-600 outline-none">
                            <option value="60">1 Hora</option>
                            <option value="120">2 Horas</option>
                        </select>
                    </div>

                    <select x-model="newApp.service" class="rounded-2xl border-4 border-gray-100 p-4 text-xl w-full font-black" required>
                        <option value="" disabled selected>Procedimento</option>
                        <option value="Botox">Botox</option>
                        <option value="Limpeza de Pele">Limpeza de Pele</option>
                        <option value="Consulta">Consulta</option>
                    </select>

                    <input type="number" x-model="newApp.price" placeholder="Preço (R$)" class="rounded-2xl border-4 border-gray-100 p-4 text-xl w-full font-black">
                    
                    <button type="submit" :disabled="submitting" class="bg-blue-600 text-white text-2xl py-5 rounded-2xl shadow-lg active:scale-95 transition-all font-black">
                        <span x-text="submitting ? 'SALVANDO...' : 'SALVAR AGENDAMENTO'"></span>
                    </button>
                </form>
            </div>

            <div class="px-2 mb-2">
                <p class="text-2xl font-black text-blue-900 uppercase tracking-tighter">
                    {{ \Carbon\Carbon::parse($selectedDate ?? now())->translatedFormat('F Y') }}
                </p>
            </div>

            <div class="mb-6 overflow-x-auto no-scrollbar flex items-center space-x-3 py-2 px-1 snap-x" style="-webkit-overflow-scrolling: touch;">
                @php $todayStr = \Carbon\Carbon::today()->format('Y-m-d'); \Carbon\Carbon::setLocale('pt_BR'); @endphp
                @for($i = 0; $i < 15; $i++)
                    @php $current = \Carbon\Carbon::today()->copy()->addDays($i); $currentStr = $current->format('Y-m-d'); @endphp
                    
                    @if($i > 0 && $i % 7 === 0)
                        <div class="flex-shrink-0 flex flex-col items-center px-1">
                            <div class="h-16 w-1.5 bg-blue-200 rounded-full"></div>
                        </div>
                    @endif

                    <button type="button" @click="changeDate('{{ $currentStr }}')" 
                       class="flex-shrink-0 w-16 h-24 rounded-2xl flex flex-col items-center justify-center border-4 transition-all snap-center shadow-md"
                       :class="{
                           'is-selected': selectedDate === '{{ $currentStr }}',
                           'is-today': '{{ $todayStr }}' === '{{ $currentStr }}',
                           'bg-white text-gray-400 border-gray-100': selectedDate !== '{{ $currentStr }}' && '{{ $todayStr }}' !== '{{ $currentStr }}'
                       }">
                        <span class="text-[12px] uppercase font-black">{{ $current->translatedFormat('D') }}</span>
                        <span class="text-2xl font-black">{{ $current->day }}</span>
                    </button>
                @endfor
            </div>

            <div class="mb-6 bg-emerald-500 rounded-3xl p-6 text-white shadow-2xl flex justify-between items-center">
                <span class="text-xl uppercase font-black">Total:</span>
                <span class="text-4xl font-black">R$ <span x-text="totalPrice"></span></span>
            </div>

            <div class="bg-gray-100 rounded-[40px] p-4 min-h-[600px] relative shadow-inner">
                <div x-show="loading" class="absolute inset-0 bg-white/50 backdrop-blur-sm z-50 flex items-center justify-center rounded-[40px]">
                    <div class="animate-spin rounded-full h-12 w-12 border-8 border-blue-600 border-t-transparent"></div>
                </div>

                <div class="relative border-l-4 border-blue-200 ml-12 space-y-2">
                    <template x-for="(hour, index) in activeHours" :key="hour">
                        <div class="relative min-h-[140px] py-4">
                            <div class="absolute -left-[75px] top-8 text-xl font-black text-gray-400" x-text="hour + ':00'"></div>
                            
                            <div class="space-y-4">
                                <template x-for="app in appointmentsByHour(hour)" :key="app.id">
                                    <div class="bg-blue-600 text-white p-6 shadow-2xl relative ml-2"
                                         :class="(app.start_time.includes(':00:00') || app.start_time.endsWith(':00')) ? 'rounded-r-3xl rounded-bl-3xl' : 'rounded-3xl'"
                                         :style="(app.start_time.includes(':00:00') || app.start_time.endsWith(':00')) ? 'clip-path: polygon(20px 0%, 100% 0%, 100% 100%, 20px 100%, 20px 55px, 0% 45px, 20px 35px);' : ''">
                                        
                                        <div class="absolute top-6 right-6">
                                            <input type="checkbox" :value="app.id" x-model="selectedIds" class="w-10 h-10 rounded-xl border-4 border-white text-red-600 bg-white/20">
                                        </div>

                                        <div :class="(app.start_time.includes(':00:00') || app.start_time.endsWith(':00')) ? 'pl-6' : ''" class="pr-12">
                                            <div class="inline-flex bg-black/30 px-4 py-2 rounded-xl mb-4 border-2 border-white/50 text-xl font-black">
                                                <span x-text="app.start_time.substring(0,5) + ' - ' + calculateEndTime(app.start_time, app.duration)"></span>
                                            </div>
                                            <p class="text-4xl leading-none mb-2 tracking-tighter font-black" x-text="'Sr(a). ' + app.client_name"></p>
                                            <p class="text-xl opacity-90 uppercase font-black" x-text="app.service"></p>
                                            <div class="mt-8 pt-4 border-t-4 border-white/20 flex justify-end items-baseline text-4xl font-black">
                                                <span class="text-sm mr-2 opacity-70">VALOR: R$</span>
                                                <span x-text="formatMoney(app.price)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        
        <button type="button" x-show="selectedIds.length > 0" @click="showModal = true" x-cloak
            class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[90] bg-red-600 text-white px-12 py-6 rounded-full text-3xl shadow-2xl active:scale-95 transition-all font-black">
            APAGAR (<span x-text="selectedIds.length"></span>)
        </button>
    </div>

    <div class="max-w-7xl mx-auto px-2 mb-4">
        <div class="bg-gray-200 rounded-2xl h-24 flex items-center justify-center border-2 border-dashed border-gray-400">
            <span class="text-gray-500 font-black text-sm uppercase">Espaço para Publicidade (Anúncio)</span>
        </div>
    </div>

    <script>
        function appointmentManager() {
            return {
                selectedDate: '{{ date('Y-m-d') }}',
                appointments: [],
                selectedIds: [],
                showModal: false,
                loading: false,
                submitting: false,
                newApp: { client_name: '', start_time: '', duration: '60', service: '', price: '' },

                autoFormatTime(val) {
                    let s = val.replace(/\D/g, '');
                    if (s.length >= 3) return s.substring(0, 2) + ':' + s.substring(2, 4);
                    return s;
                },

                async fetchAppointments() {
                    this.loading = true;
                    try {
                        const res = await fetch('/api/appointments?date=' + this.selectedDate);
                        this.appointments = await res.json();
                    } catch (e) { console.error(e); }
                    this.loading = false;
                },

                async addAppointment() {
                    if (this.newApp.start_time) {
                        let t = this.newApp.start_time.replace(':', '');
                        if (t.length <= 2) this.newApp.start_time = t.padStart(2, '0') + ':00';
                    }

                    this.submitting = true;
                    try {
                        const res = await fetch('{{ route('appointments.store') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify({ ...this.newApp, date: this.selectedDate })
                        });
                        if (res.ok) {
                            this.newApp = { client_name: '', start_time: '', duration: '60', service: '', price: '' };
                            await this.fetchAppointments();
                        }
                    } catch (e) { console.error(e); }
                    this.submitting = false;
                },

                async confirmDelete() {
                    this.showModal = false;
                    for (const id of this.selectedIds) {
                        await fetch('/appointments/' + id, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        });
                    }
                    await this.fetchAppointments();
                    this.selectedIds = [];
                },

                calculateEndTime(startTime, duration) {
                    let [h, m] = startTime.split(':').map(Number);
                    let total = h * 60 + m + parseInt(duration);
                    return String(Math.floor(total / 60) % 24).padStart(2, '0') + ':' + String(total % 60).padStart(2, '0');
                },

                get activeHours() {
                    let base = ['09','10','11','12','13','14','15','16','17','18'];
                    let apps = this.appointments.map(a => a.start_time.split(':')[0]);
                    return [...new Set([...base, ...apps])].sort();
                },
                changeDate(date) { this.selectedDate = date; this.selectedIds = []; this.fetchAppointments(); },
                appointmentsByHour(h) { return this.appointments.filter(a => a.start_time.startsWith(h + ':')); },
                get totalPrice() { return this.formatMoney(this.appointments.reduce((s, a) => s + Number(a.price || 0), 0)); },
                formatMoney(v) { return Number(v).toLocaleString('pt-BR', { minimumFractionDigits: 2 }); }
            }
        }
    </script>
</x-app-layout>