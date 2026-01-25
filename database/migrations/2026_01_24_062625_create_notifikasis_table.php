<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
{
    Schema::create('notifikasis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Penerima Notif
        $table->string('judul');      // Contoh: "Peringatan Poin"
        $table->text('pesan');        // Contoh: "Siswa A poinnya 25"
        $table->string('link')->nullable(); // Link redirect saat diklik
        $table->string('jenis')->default('info'); // info, warning, danger
        $table->boolean('is_read')->default(false); // Status sudah dibaca/belum
        $table->timestamps();
    });
}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
