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
    Schema::table('siswas', function (Blueprint $table) {
        $table->string('status_sanksi')->nullable()->after('foto'); 
        // Menyimpan teks seperti: "Piket", "Skorsing", "Teguran Lisan"
    });
}

public function down()
{
    Schema::table('siswas', function (Blueprint $table) {
        $table->dropColumn('status_sanksi');
    });
}
};
