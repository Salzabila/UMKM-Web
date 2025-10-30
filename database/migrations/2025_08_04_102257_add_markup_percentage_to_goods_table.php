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
            // Add markup_percentage column as a decimal with 5 total digits and 2 decimal places
            // It's nullable because existing goods might not have a manual markup,
            // and new goods might use automatic markup if this field is left empty.
            $table->decimal('markup_percentage', 5, 2)->nullable()->after('harga')->comment('Manual markup percentage set by user');
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
            $table->dropColumn('markup_percentage');
        });
    }
};
