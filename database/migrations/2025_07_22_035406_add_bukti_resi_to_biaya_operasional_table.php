<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('biaya_operasional', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan path gambar, setelah kolom 'qty'
            $table->string('bukti_resi')->nullable()->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('biaya_operasional', function (Blueprint $table) {
            $table->dropColumn('bukti_resi');
        });
    }
};
