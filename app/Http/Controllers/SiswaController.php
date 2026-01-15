<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
  public function index(Request $request)
    {
        // 1. Mulai Query Builder
        $query = Siswa::with('kelas');

        // 2. Logika Pencarian (NIS atau Nama)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        // 3. Logika Filter Jenis Kelamin
        if ($request->filled('jk')) {
            $query->where('jenis_kelamin', $request->jk);
        }

        // 4. Logika Filter Kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // 5. Eksekusi Query & Pagination
        // append() berguna agar saat pindah halaman (page 2), filter tidak hilang
        $siswas = $query->latest()->paginate(10)->appends($request->query());
        
        // Data kelas untuk dropdown filter & modal
        $kelas = Kelas::all(); 

        return view('master.siswa.index', compact('siswas', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'           => 'required|numeric|unique:siswas,nis',
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id'      => 'required|exists:kelas,id',
        ]);

        Siswa::create($request->only(['nis', 'nama', 'jenis_kelamin', 'kelas_id']));

        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan.');
    }

   public function update(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'nis'           => 'required|numeric|unique:siswas,nis,' . $id,
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id'      => 'required|exists:kelas,id',
            // Validasi Data Tambahan (Nullable semua agar tidak wajib diisi)
            'tempat_lahir'  => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string',
            'nama_ayah'     => 'nullable|string',
            'nama_ibu'      => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $siswa = Siswa::findOrFail($id);
        $data = $request->all();

        // 2. Logic Upload Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada (opsional, perlu import Storage facade)
            // if ($siswa->foto && \Storage::exists('public/' . $siswa->foto)) {
            //    \Storage::delete('public/' . $siswa->foto);
            // }
            
            // Simpan foto baru
            $path = $request->file('foto')->store('foto_siswa', 'public');
            $data['foto'] = $path;
        }

        // 3. Update Database
        $siswa->update($data);

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus.');
    }

   public function exportExcel(Request $request)
{
    // Hapus output buffer sebelumnya agar file binary bersih
    if (ob_get_contents()) ob_end_clean(); 
    
    return Excel::download(new SiswaExport($request), 'data-siswa.xlsx');
}

    // METHOD PDF
    public function exportPdf(Request $request)
    {
        // Logic Query (Sama persis dengan index/export excel)
        $query = Siswa::with('kelas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jk')) {
            $query->where('jenis_kelamin', $request->jk);
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Ambil semua data (get), bukan paginate
        $siswas = $query->latest()->get();

        $pdf = Pdf::loadView('master.siswa.pdf', compact('siswas'));
        // download() untuk unduh langsung, stream() untuk preview di browser
        return $pdf->download('laporan-siswa.pdf');
    }
}