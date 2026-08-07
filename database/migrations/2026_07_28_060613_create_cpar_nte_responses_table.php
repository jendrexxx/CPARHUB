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
        Schema::create('cpar_nte_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nte_id')->nullable();
            $table->string('employee_no')->nullable();
            $table->string('response_attachment')->nullable();
            $table->string('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_nte_responses');
    }
};
