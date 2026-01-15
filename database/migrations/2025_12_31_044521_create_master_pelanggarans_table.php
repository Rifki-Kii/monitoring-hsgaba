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
    Schema::create('master_pelanggarans', function (Blueprint $table) {
        $table->id();
        $table->string('jenis_pelanggaran'); // Contoh: Terlambat, Tidak Piket
        $table->integer('poin');             // Contoh: 2, 5, 10
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pelanggarans');
    }
};
