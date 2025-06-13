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
    Schema::table('animals', function (Blueprint $table) {
        $table->float('weight')->nullable()->after('age')->comment('Peso en kg');
    });
}

public function down()
{
    Schema::table('animals', function (Blueprint $table) {
        $table->dropColumn('weight');
    });
}

};
