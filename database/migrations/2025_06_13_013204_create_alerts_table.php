<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['health', 'schedule', 'production', 'maintenance']);
            $table->enum('severity', ['low', 'medium', 'high'])->default('low');
            $table->string('message');
            $table->foreignUuid('animal_id')->nullable()->constrained('animals')->nullOnDelete();
            $table->dateTime('date');
            $table->boolean('resolved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
