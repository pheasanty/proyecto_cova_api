<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'manager', 'operator', 'viewer'])->default('viewer');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->string('avatar')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('department')->nullable();
            $table->date('join_date');
            $table->dateTime('last_login')->nullable();
            $table->rememberToken();         // para auth Laravel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
