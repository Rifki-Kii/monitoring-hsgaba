<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User; 
use App\Models\Notifikasi; // <--- WAJIB: Model Notifikasi
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Auth; // <--- WAJIB: Auth Facade

class Navbar extends Component
{
    public $title;

    // --- 1. STATE UNTUK NOTIFIKASI (BARU) ---
    public $notifikasiList = [];
    public $unreadCount = 0;

    // Listener menangkap sinyal 'refreshNotif' dari halaman lain
    protected $listeners = ['refreshNotif' => 'loadNotifikasi'];

    // --- 2. STATE UNTUK MODAL WA (LAMA) ---
    public $showWaModal = false;
    public $targetAudience = 'all'; 
    public $pesanReminder = "Yth. Bapak/Ibu Guru, Mohon segera melengkapi input Nilai Akademik dan Poin Kedisiplinan siswa sebelum tanggal pelaporan. Silahkan akses : https://projectki.site Terima kasih.";
    public $isSending = false;

    public function mount($title = 'Dashboard')
    {
        $this->title = $title;
        $this->loadNotifikasi(); // Load notifikasi saat navbar muncul
    }

    // ==========================================
    // LOGIKA NOTIFIKASI
    // ==========================================
    public function loadNotifikasi()
    {
        if (Auth::check()) {
            // Ambil 10 notifikasi terakhir
            $this->notifikasiList = Notifikasi::where('user_id', Auth::id())
                                    ->latest()
                                    ->take(10)
                                    ->get();
            
            // Hitung yang belum dibaca
            $this->unreadCount = Notifikasi::where('user_id', Auth::id())
                                    ->where('is_read', false)
                                    ->count();
        }
    }

    public function markAsRead($id)
    {
        $notif = Notifikasi::find($id);
        
        // Pastikan notif milik user yang sedang login
        if ($notif && $notif->user_id == Auth::id()) {
            $notif->update(['is_read' => true]);
            
            // Jika ada link, redirect ke sana
            if ($notif->link) {
                return redirect($notif->link);
            }
        }
        
        $this->loadNotifikasi(); // Refresh list
    }

    public function markAllRead()
    {
        Notifikasi::where('user_id', Auth::id())->update(['is_read' => true]);
        $this->loadNotifikasi();
    }

    // ==========================================
    // LOGIKA BROADCAST WA (KODE LAMA ANDA)
    // ==========================================
    public function openWaModal()
    {
        $this->showWaModal = true;
    }

    public function closeWaModal()
    {
        $this->showWaModal = false;
    }

    public function sendBroadcast()
    {
        // 1. Validasi Input
        $this->validate([
            'targetAudience' => 'required',
            'pesanReminder' => 'required|min:5'
        ]);

        $this->isSending = true;

        // 2. Query Nomor HP Target
        $query = User::whereNotNull('nomor_hp') 
                     ->where('nomor_hp', '!=', '') 
                     ->where('role', '!=', 'admin'); 

        // Filter berdasarkan pilihan radio button
        if ($this->targetAudience == 'walikelas') {
            $query->where('role', 'wali_kelas'); 
        } elseif ($this->targetAudience == 'guru_mapel') {
            $query->where('role', 'guru');
        }

        // Ambil daftar nomor HP
        $targets = $query->pluck('nomor_hp')->toArray(); 

        // Cek jika tidak ada nomor
        if (empty($targets)) {
            $this->dispatch('notify-error', message: 'Tidak ada data nomor HP user ditemukan.');
            $this->isSending = false;
            return;
        }

        // 3. PROSES KIRIM KE FONNTE
        $targetString = implode(',', $targets);

        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $targetString,
                'message' => $this->pesanReminder . "\n\n_Dikirim otomatis oleh Sistem informasi Monitoring nilai akademik dan poin kedisiplinan siswa_",
                'countryCode' => '62', 
            ]);

            $resBody = $response->json();

            if ($response->successful() && isset($resBody['status']) && $resBody['status'] == true) {
                $jumlah = count($targets);
                $this->dispatch('notify-success', message: "Berhasil mengirim ke $jumlah orang.");
                $this->showWaModal = false; 
            } else {
                $this->dispatch('notify-error', message: 'Gagal kirim: ' . ($resBody['reason'] ?? 'Cek koneksi WhatsApp Admin'));
            }

        } catch (\Exception $e) {
            $this->dispatch('notify-error', message: 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }

        $this->isSending = false;
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}