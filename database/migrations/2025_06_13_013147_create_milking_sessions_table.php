<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('milking_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedDecimal('yield', 6, 2);                          // litros ordeñados
            $table->enum('quality', ['excellent', 'good', 'fair', 'poor']);
            $table->text('notes')->nullable();
            $table->unsignedDecimal('temperature', 4, 1);                   // °C
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milking_sessions');
    }
};
