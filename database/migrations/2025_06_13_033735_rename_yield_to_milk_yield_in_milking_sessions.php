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
    Schema::table('milking_sessions', function (Blueprint $table) {
        $table->renameColumn('yield', 'milk_yield');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('milk_yield_in_milking_sessions', function (Blueprint $table) {
            //
        });
    }
};
