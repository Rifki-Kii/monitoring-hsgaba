<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up()
{
    Schema::table('nilais', function (Blueprint $table) {
        // Kolom untuk nilai gabungan (Kognitif + Keterampilan) / 2
        $table->decimal('nilai_raport', 5, 2)->nullable()->after('keterampilan');
    });
}

public function down()
{
    Schema::table('nilais', function (Blueprint $table) {
        $table->dropColumn('nilai_raport');
    });
}
};
