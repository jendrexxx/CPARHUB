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
            $table->string('action')->nullable();
            $table->string('old_status')->nullable();;
            $table->string('new_status')->nullable();;
            $table->string('reported_by')->nullable();
            $table->string('assigned_id')->nullable();
            $table->text('remarks')->nullable();
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
