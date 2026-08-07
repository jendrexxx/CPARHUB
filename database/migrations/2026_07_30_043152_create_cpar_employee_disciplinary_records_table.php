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
        Schema::create('cpar_employee_disciplinary_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('assignment_id');
            // Multiple selected categories
            $table->json('discipline_ids')->nullable();
            // Multiple selected offense levels
            $table->json('offense_ids')->nullable();
            // Multiple selected HR decisions
            $table->json('decision_ids')->nullable();
            $table->date('incident_date');
            $table->date('valid_until');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('status')->default('DRAFT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_employee_disciplinary_records');
    }
};
