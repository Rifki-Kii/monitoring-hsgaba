<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TentangController extends Controller
{
    public function index()
    {
        // Data ini bisa diambil dari database jika mau dinamis, 
        // tapi untuk halaman 'About' statis biasanya sudah cukup.
        return view('tentang.index', [
            'appName' => 'SIMONK (Sistem Monitoring Akademik & Kedisiplinan)',
            'version' => '1.0.0 (Beta)',
            'school'  => 'Homeschooling Group Abdurrahman Bin Auf',
            'devName' => 'Rifki Maulana',
            'devNim'  => '2211010010',
            'campus'  => 'STIKOM El Rahma Bogor',
            'year'    => date('Y')
        ]);
    }
}