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
        Schema::create('cpar_lab_acknowledgments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('assignment_id');
            $table->string('acknowledged_by')->nullable();
            $table->string('acknowledged_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_lab_acknowledgments');
    }
};
