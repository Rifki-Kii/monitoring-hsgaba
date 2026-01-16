<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User; // Pastikan Model User ada
use Illuminate\Support\Facades\Http; // Wajib untuk kirim API

class Navbar extends Component
{
    public $title;

    // STATE UNTUK MODAL WA
    public $showWaModal = false;
    public $targetAudience = 'all'; 
    public $pesanReminder = "Yth. Bapak/Ibu Guru, Mohon segera melengkapi input Nilai Akademik dan Poin Kedisiplinan siswa sebelum tanggal pelaporan. Silahkan akses : https://projectki.site Terima kasih.";
    public $isSending = false;

    public function mount($title = 'Dashboard')
    {
        $this->title = $title;
    }

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

        // 2. Query Nomor HP Target (SUDAH DISESUAIKAN: nomor_hp)
        $query = User::whereNotNull('nomor_hp') // <--- GANTI JADI nomor_hp
                     ->where('nomor_hp', '!=', '') // <--- GANTI JADI nomor_hp
                     ->where('role', '!=', 'admin'); 

        // Filter berdasarkan pilihan radio button
        if ($this->targetAudience == 'walikelas') {
            $query->where('role', 'wali_kelas'); 
        } elseif ($this->targetAudience == 'guru_mapel') {
            $query->where('role', 'guru');
        }

        // Ambil daftar nomor HP (SUDAH DISESUAIKAN)
        $targets = $query->pluck('nomor_hp')->toArray(); // <--- GANTI JADI nomor_hp

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
                'message' => $this->pesanReminder . "\n\n_Dikirim otomatis oleh Sistem informasi Monitoring nilai akademik dan poin kedisiplinan siswa ",
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
            $this->dispatch('notify-error', message: 'Terjadi kesalahan sistem.');
        }

        $this->isSending = false;
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}