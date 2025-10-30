<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->integer('min_qty_grosir')->nullable()->after('harga');
            $table->integer('harga_grosir')->nullable()->after('min_qty_grosir');
            $table->boolean('is_grosir_active')->default(false)->after('harga_grosir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->dropColumn(['min_qty_grosir', 'harga_grosir', 'is_grosir_active']);
        });
    }
};
