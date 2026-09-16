<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_user', function (Blueprint $table) {
            $table->foreignId('list_id')->constrained('lists')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->primary(['list_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_user');
    }
};
