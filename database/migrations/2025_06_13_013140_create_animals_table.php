<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('tag')->unique();
            $table->string('breed')->nullable();
            $table->unsignedSmallInteger('age')->nullable();
            $table->enum('health_status', ['healthy', 'sick', 'attention'])->default('healthy');
            $table->dateTime('last_milking')->nullable();
            $table->unsignedDecimal('total_production', 8, 2)->default(0);   // litros
            $table->unsignedDecimal('average_daily', 6, 2)->default(0);      // litros/día
            $table->string('image')->nullable();                             // URL o path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
