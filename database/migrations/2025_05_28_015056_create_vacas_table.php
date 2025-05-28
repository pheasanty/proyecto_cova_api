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
Schema::create('vacas', function (Blueprint $table) {
    $table->id();
    $table->string('nombre'); // o código identificador
    $table->string('raza')->nullable();
    $table->date('fecha_nacimiento')->nullable();
    $table->string('estado')->default('activa'); // activa, vendida, muerta, etc.
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
        Schema::dropIfExists('vacas');
    }
};
