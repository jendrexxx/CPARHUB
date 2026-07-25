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
        Schema::create('cpar_request_forms', function (Blueprint $table) {
            $table->id();
            $table->string('cpar_no')->unique();
            $table->string('employee_no');
            $table->string('reported_by');
            $table->string('date_open');
            $table->integer('source_id');
            $table->integer('complaint_category_id');
            $table->integer('concern_category_id');
            $table->string('complainant_name');
            $table->longText('concern_description');
            $table->integer('department_id');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_request_forms');
    }
};
