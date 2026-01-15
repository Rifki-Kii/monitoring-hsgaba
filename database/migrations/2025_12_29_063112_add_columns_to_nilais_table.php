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
        $table->integer('tugas')->nullable()->after('uh3'); // Nilai B
        $table->integer('keterampilan')->nullable()->after('nilai_akhir'); // Kolom praktek
        // Kita anggap UTS = PTS, dan UAS = PAS supaya tidak perlu rename kolom
    });
}

public function down()
{
    Schema::table('nilais', function (Blueprint $table) {
        $table->dropColumn(['tugas', 'keterampilan']);
    });
}
};
