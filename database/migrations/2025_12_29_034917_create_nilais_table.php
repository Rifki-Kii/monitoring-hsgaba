<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('nilais', function (Blueprint $table) {
        $table->id();
        $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
        $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
        $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
        $table->foreignId('guru_id')->constrained('users')->onDelete('cascade'); // Siapa yang input
        
        $table->string('tahun_ajaran'); // Contoh: "2024/2025"
        $table->enum('semester', ['ganjil', 'genap']);

        // Komponen Nilai (Bisa null jika belum diisi)
        $table->integer('uh1')->nullable(); 
        $table->integer('uh2')->nullable();
        $table->integer('uh3')->nullable(); // Opsional
        $table->integer('uts')->nullable();
        $table->integer('uas')->nullable();

        // Hasil Kalkulasi
        $table->decimal('nilai_akhir', 5, 2)->nullable(); // Total skor (0-100)
        $table->string('predikat')->nullable(); // A, B, C
        $table->string('status')->nullable(); // Tuntas / Remedial
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
