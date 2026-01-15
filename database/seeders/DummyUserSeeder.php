<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyUserSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. DATA USERS (Tanpa Email, Sesuai Error)
        // ==========================================
        echo "1. Seeding Users...\n";
        DB::table('users')->insert([
            [
                'nama' => 'Admin Sistem',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nomor_hp' => '081111111111',
                // 'email' dihapus
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Guru User',
                'username' => 'guru',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'nomor_hp' => '082222222222',
                // 'email' dihapus
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Wali Kelas User',
                'username' => 'wali',
                'password' => Hash::make('wali123'),
                'role' => 'wali_kelas',
                'nomor_hp' => '083333333333',
                // 'email' dihapus
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
        ]);

        // ==========================================
        // 2. DATA MAPELS
        // ==========================================
        echo "2. Seeding Mapels...\n";
        DB::table('mapels')->insert([
            ['id' => 1, 'kode_mapel' => 'MTK-01', 'nama_mapel' => 'Matematika', 'kategori' => 'Pengetahuan Umum', 'kkm' => 75, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'kode_mapel' => 'IPA-01', 'nama_mapel' => 'Ilmu Pengetahuan Alam', 'kategori' => 'Pengetahuan Umum', 'kkm' => 75, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'kode_mapel' => 'PAI-01', 'nama_mapel' => 'Pendidikan Agama Islam', 'kategori' => 'Tsaqafah Islam', 'kkm' => 80, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'kode_mapel' => 'ING-01', 'nama_mapel' => 'Bahasa Inggris', 'kategori' => 'Pengetahuan Umum', 'kkm' => 70, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'kode_mapel' => 'PJK-01', 'nama_mapel' => 'PJOK', 'kategori' => 'Keterampilan', 'kkm' => 78, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // ==========================================
        // 3. DATA GURUS
        // ==========================================
        echo "3. Seeding Gurus...\n";
        DB::table('gurus')->insert([
            ['id' => 1, 'nip' => '198501012010011001', 'nama_guru' => 'Budi Santoso, S.Pd', 'email' => 'budi@sekolah.id', 'no_hp' => '081234567890', 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Merpati No. 10', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'nip' => '199002022015022002', 'nama_guru' => 'Siti Aminah, S.Ag', 'email' => 'siti@sekolah.id', 'no_hp' => '081298765432', 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Kutilang No. 5', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'nip' => '198803032012031003', 'nama_guru' => 'Rahmat Hidayat, M.Sc', 'email' => 'rahmat@sekolah.id', 'no_hp' => '081345678901', 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Kenari No. 12', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'nip' => '199504042018042004', 'nama_guru' => 'Dewi Sartika, S.Pd', 'email' => 'dewi@sekolah.id', 'no_hp' => '081398765431', 'jenis_kelamin' => 'P', 'alamat' => 'Jl. Mawar No. 3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'nip' => null, 'nama_guru' => 'Fajar Nugraha, S.Or', 'email' => 'fajar@sekolah.id', 'no_hp' => '081567890123', 'jenis_kelamin' => 'L', 'alamat' => 'Jl. Melati No. 8', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // ==========================================
        // 4. DATA KELAS
        // ==========================================
        echo "4. Seeding Kelas...\n";
        DB::table('kelas')->insert([
            ['id' => 1, 'nama_kelas' => '7A', 'jenjang' => 'SMP', 'wali_guru_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'nama_kelas' => '7B', 'jenjang' => 'SMP', 'wali_guru_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'nama_kelas' => '8A', 'jenjang' => 'SMP', 'wali_guru_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'nama_kelas' => '8B', 'jenjang' => 'SMP', 'wali_guru_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'nama_kelas' => '9A', 'jenjang' => 'SMP', 'wali_guru_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // ==========================================
        // 5. PIVOT GURU_MAPEL
        // ==========================================
        echo "5. Seeding Guru Mapel...\n";
        DB::table('guru_mapel')->insert([
            ['guru_id' => 1, 'mapel_id' => 1],
            ['guru_id' => 2, 'mapel_id' => 3],
            ['guru_id' => 3, 'mapel_id' => 2],
            ['guru_id' => 3, 'mapel_id' => 1],
            ['guru_id' => 4, 'mapel_id' => 4],
            ['guru_id' => 5, 'mapel_id' => 5],
        ]);

        // ==========================================
        // 6. DATA SISWA
        // ==========================================
        echo "6. Seeding Siswa...\n";
        
        DB::table('siswas')->insert([
            // --- Kelas 7A (ID: 1) ---
            [
                'nis' => '23247001',
                'nama' => 'Adit Sopo Jarwo',
                'jenis_kelamin' => 'L',
                'kelas_id' => 1,
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2010-05-12',
                'alamat' => 'Jl. Kebon Jeruk No. 1',
                'nama_ayah' => 'Slamet',
                'nama_ibu' => 'Susi',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'nis' => '23247002',
                'nama' => 'Bunga Citra Lestari',
                'jenis_kelamin' => 'P',
                'kelas_id' => 1,
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2010-08-17',
                'alamat' => 'Jl. Braga No. 12',
                'nama_ayah' => 'Agus',
                'nama_ibu' => 'Rina',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],

            // --- Kelas 7B (ID: 2) ---
            [
                'nis' => '23247003',
                'nama' => 'Candra Wijaya',
                'jenis_kelamin' => 'L',
                'kelas_id' => 2,
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2010-02-20',
                'alamat' => 'Jl. Pemuda No. 88',
                'nama_ayah' => 'Bambang',
                'nama_ibu' => 'Yuni',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'nis' => '23247004',
                'nama' => 'Dinda Hauw',
                'jenis_kelamin' => 'P',
                'kelas_id' => 2,
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '2010-11-10',
                'alamat' => 'Jl. Ijen No. 4',
                'nama_ayah' => 'Hendra',
                'nama_ibu' => 'Lilis',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],

            // --- Kelas 8A (ID: 3) ---
            [
                'nis' => '22238001',
                'nama' => 'Eko Patrio',
                'jenis_kelamin' => 'L',
                'kelas_id' => 3,
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2009-01-01',
                'alamat' => 'Jl. Simpang Lima No. 5',
                'nama_ayah' => 'Joko',
                'nama_ibu' => 'Tini',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'nis' => '22238002',
                'nama' => 'Fitri Tropica',
                'jenis_kelamin' => 'P',
                'kelas_id' => 3,
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '2009-06-15',
                'alamat' => 'Jl. Merdeka No. 9',
                'nama_ayah' => 'Rudi',
                'nama_ibu' => 'Sari',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],

            // --- Kelas 8B (ID: 4) ---
            [
                'nis' => '22238003',
                'nama' => 'Gilang Dirga',
                'jenis_kelamin' => 'L',
                'kelas_id' => 4,
                'tempat_lahir' => 'Palembang',
                'tanggal_lahir' => '2009-12-25',
                'alamat' => 'Jl. Ampera No. 100',
                'nama_ayah' => 'Udin',
                'nama_ibu' => 'Imah',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'nis' => '22238004',
                'nama' => 'Hesti Purwadinata',
                'jenis_kelamin' => 'P',
                'kelas_id' => 4,
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => '2009-03-30',
                'alamat' => 'Jl. Pajajaran No. 20',
                'nama_ayah' => 'Asep',
                'nama_ibu' => 'Euis',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],

            // --- Kelas 9A (ID: 5) ---
            [
                'nis' => '21229001',
                'nama' => 'Indra Bekti',
                'jenis_kelamin' => 'L',
                'kelas_id' => 5,
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-07-07',
                'alamat' => 'Jl. Sudirman No. 1',
                'nama_ayah' => 'Tono',
                'nama_ibu' => 'Marni',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'nis' => '21229002',
                'nama' => 'Jessica Mila',
                'jenis_kelamin' => 'P',
                'kelas_id' => 5,
                'tempat_lahir' => 'Aceh',
                'tanggal_lahir' => '2008-09-09',
                'alamat' => 'Jl. Iskandar Muda No. 3',
                'nama_ayah' => 'Heru',
                'nama_ibu' => 'Wati',
                'foto' => null,
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
        ]);
    }
}