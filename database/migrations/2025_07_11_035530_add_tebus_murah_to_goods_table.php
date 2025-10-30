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
            $table->boolean('is_tebus_murah_active')->default(false)->after('is_grosir_active');
            $table->decimal('min_total_tebus_murah', 15, 2)->nullable()->after('is_tebus_murah_active');
            $table->decimal('harga_tebus_murah', 15, 2)->nullable()->after('min_total_tebus_murah');
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
            $table->dropColumn(['is_tebus_murah_active', 'min_total_tebus_murah', 'harga_tebus_murah']);
        });
    }
};
