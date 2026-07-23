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
        Schema::create('cpar_assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('cpar_id');
            $table->integer('assigned_to');
            $table->integer('department_id');
            $table->dateTime('assigned_date');
            $table->integer('reassigned_to')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_assignments');
    }
};
