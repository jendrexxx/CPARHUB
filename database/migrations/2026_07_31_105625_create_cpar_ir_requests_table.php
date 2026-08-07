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
        Schema::create('cpar_ir_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assignment_ir_id');
            $table->string('ir_id');
            $table->string('employee_no')->nullable();
            $table->string('ir_attachment')->nullable();
            $table->string('issued_at')->nullable();
            $table->string('due_date')->nullable();
            $table->text('status')->nullable();
            $table->string('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_ir_requests');
    }
};
