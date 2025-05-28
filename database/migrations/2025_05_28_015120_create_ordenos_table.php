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
        Schema::create('ordenos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaca_id')->constrained('vacas')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora');
            $table->decimal('cantidad_litros', 5, 2);
            $table->string('responsable');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenos');
    }
};
