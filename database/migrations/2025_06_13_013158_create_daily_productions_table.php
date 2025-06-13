<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_productions', function (Blueprint $table) {
            $table->date('date')->primary();                                 // clave natural
            $table->unsignedDecimal('total_yield', 8, 2);
            $table->unsignedInteger('session_count');
            $table->unsignedDecimal('average_yield', 6, 2);

            $table->unsignedInteger('excellent')->default(0);
            $table->unsignedInteger('good')->default(0);
            $table->unsignedInteger('fair')->default(0);
            $table->unsignedInteger('poor')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_productions');
    }
};
