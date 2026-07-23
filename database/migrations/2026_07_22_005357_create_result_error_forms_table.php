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
        Schema::create('result_error_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('result_no')->unique();
            $table->string('employee_no');
            $table->string('report_reciepient');
            $table->string('date_reported');
            $table->text('patient_name');
            $table->text('attending_physician');
            $table->text('test_procedure');
            $table->string('actual_released_date');
            $table->text('source_of_information');
            $table->text('data_information')->nullable();
            $table->text('technical_information')->nullable();
            $table->text('quality_information')->nullable();
            $table->string('complainant_category');
            $table->text('complain_name');
            $table->text('concern_description');
            $table->integer('status_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_error_forms');
    }
};
