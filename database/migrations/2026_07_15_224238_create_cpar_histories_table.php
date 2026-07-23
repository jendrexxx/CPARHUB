<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cpar_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpar_id');
            $table->foreignId('status_id');
            $table->text('remarks')->nullable();
            $table->foreignId('action_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_histories');
    }
};
