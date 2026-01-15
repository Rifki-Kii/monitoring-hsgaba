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
    Schema::table('nilais', function (Blueprint $table) {
        // Hapus kolom UH satuan
        $table->dropColumn(['uh1', 'uh2', 'uh3']);
        
        // Tambah kolom Rata-rata UH
        // Kita taruh setelah guru_id atau sesuaikan urutan
        $table->integer('rata_uh')->nullable()->after('guru_id'); 
    });
}

public function down()
{
    Schema::table('nilais', function (Blueprint $table) {
        $table->integer('uh1')->nullable();
        $table->integer('uh2')->nullable();
        $table->integer('uh3')->nullable();
        $table->dropColumn('rata_uh');
    });
}
};
