<nav class="w-full bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between sticky top-0 z-30 shadow-sm" x-data="{ userOpen: false, notifOpen: false }">
    
    <div class="flex items-center space-x-4">
        <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" onclick="document.getElementById('sidebar').classList.toggle('hidden')">
            <i class="fas fa-bars text-gray-700 text-lg"></i>
        </button>
        <div class="hidden md:flex items-center space-x-2">
            <a href="/dashboard" class="text-sm text-gray-500 hover:text-gray-700 transition-colors"><i class="fas fa-home text-xs"></i></a>
            <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
            <span class="text-sm font-medium text-gray-700">{{ $title }}</span>
        </div>
        <h1 class="md:hidden text-lg font-semibold text-gray-900 truncate max-w-[180px]">{{ $title }}</h1>
    </div>

    <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2">
        <h1 class="text-lg font-semibold text-gray-900">{{ $title }}</h1>
    </div>

    <div class="flex items-center space-x-4">
        
        {{-- Notification Bell (Static for now) --}}
        <div class="relative">
            <button @click="notifOpen = !notifOpen; userOpen = false" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors group">
                <i class="fas fa-bell text-gray-600 group-hover:text-gray-800 text-lg"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            </button>
            {{-- Dropdown Notif --}}
            <div x-show="notifOpen" @click.away="notifOpen = false" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-40" :class="{'hidden': !notifOpen}">
                <div class="p-4 border-b border-gray-100"><h3 class="font-semibold text-gray-900">Notifikasi</h3></div>
                <div class="p-4 text-center text-sm text-gray-500">Belum ada notifikasi baru.</div>
            </div>
        </div>

        {{-- User Profile Dropdown --}}
        <div class="relative">
            <button @click="userOpen = !userOpen; notifOpen = false" class="flex items-center space-x-3 p-1 rounded-lg hover:bg-gray-100 transition-colors group">
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white text-sm font-medium">
                    {{ substr(auth()->user()->nama, 0, 1) }}
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->nama }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <i class="fas fa-chevron-down text-gray-500 text-xs hidden md:block group-hover:text-gray-700"></i>
            </button>

            {{-- User Menu --}}
            <div x-show="userOpen" @click.away="userOpen = false" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 z-40" :class="{'hidden': !userOpen}">
                <div class="p-4 border-b border-gray-100">
                    <p class="font-medium text-gray-900">{{ auth()->user()->nama }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                </div>

                <div class="py-2">
                    {{-- MENU 1: BROADCAST WA (BARU) --}}
                    <button wire:click="openWaModal" class="w-full text-left flex items-center space-x-3 px-4 py-2.5 text-sm text-emerald-600 hover:bg-emerald-50 transition">
                        <i class="fab fa-whatsapp w-4 text-lg"></i>
                        <span class="font-bold">Kirim Reminder WA</span>
                    </button>

                    <div class="border-t border-gray-100 my-1"></div>

                    {{-- Menu Standar --}}
                    <a href="/profile" class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-user text-gray-500 w-4"></i><span>Profil Saya</span>
                    </a>
                    
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center space-x-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt text-red-500 w-4"></i><span>Keluar</span>
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL WA --}}
    @if($showWaModal)
    <div class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-900/70 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-100 transition-all">
            <div class="bg-emerald-600 p-5 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fab fa-whatsapp text-2xl text-white"></i>
                    <div><h3 class="text-white font-bold text-lg">Broadcast Reminder</h3><p class="text-emerald-100 text-xs">Kirim pesan ke Guru/Wali Kelas</p></div>
                </div>
                <button wire:click="closeWaModal" class="text-emerald-200 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6">
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-5 flex gap-3 items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <p class="text-xs text-blue-600 leading-relaxed">Pesan dikirim ke nomor WhatsApp yang terdaftar di database.</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Target</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="targetAudience" value="all" class="peer sr-only">
                                <div class="text-center p-2 rounded-lg border border-slate-200 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 hover:bg-slate-50"><span class="block text-xs font-bold">Semua</span></div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="targetAudience" value="walikelas" class="peer sr-only">
                                <div class="text-center p-2 rounded-lg border border-slate-200 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 hover:bg-slate-50"><span class="block text-xs font-bold">Wali Kelas</span></div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="targetAudience" value="guru_mapel" class="peer sr-only">
                                <div class="text-center p-2 rounded-lg border border-slate-200 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 hover:bg-slate-50"><span class="block text-xs font-bold">Guru</span></div>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Isi Pesan</label>
                        <textarea wire:model="pesanReminder" rows="4" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Isi pesan..."></textarea>
                    </div>
                </div>
                <div class="mt-8 pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button wire:click="closeWaModal" class="px-4 py-2 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-lg">Batal</button>
                    <button wire:click="sendBroadcast" wire:loading.attr="disabled" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold shadow-lg shadow-emerald-200 flex items-center gap-2">
                        <span wire:loading.remove wire:target="sendBroadcast"><i class="fas fa-paper-plane"></i> Kirim</span>
                        <span wire:loading wire:target="sendBroadcast"><i class="fas fa-circle-notch fa-spin"></i> Mengirim...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Script Notifikasi Toast (SweetAlert Style Manual) --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('notify-success', (event) => {
                alert(event.message); // Atau ganti dengan SweetAlert jika ada
            });
            @this.on('notify-error', (event) => {
                alert(event.message);
            });
        });
    </script>
</nav>