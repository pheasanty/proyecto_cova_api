<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->foreignUuid('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->primary(['permission_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_user');
    }
};
