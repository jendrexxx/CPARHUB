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
        Schema::create('cpar_investigations', function (Blueprint $table) {
            $table->id();
            $table->integer('cpar_id');
            $table->longText('identified_cause');
            $table->longText('provided_solution');
            $table->longText('recommendation')->nullable();
            $table->longText('action_taken_by');
            $table->date('date_completed')->nullable();
            $table->integer('tat')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_investigations');
    }
};
