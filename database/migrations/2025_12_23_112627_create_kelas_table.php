<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('kelas', function (Blueprint $table) {
        $table->id();
        $table->string('nama_kelas'); // Contoh: 7A
        $table->enum('jenjang', ['SD', 'SMP']);
        
        // Relasi: Satu Kelas punya Satu Wali Guru
        $table->foreignId('wali_guru_id')
              ->nullable()
              ->constrained('gurus')
              ->nullOnDelete(); // Jika guru dihapus, kelasnya jadi tidak punya wali (tidak ikut terhapus)

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
