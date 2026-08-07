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
        Schema::create('cpar_notice_to_explains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('assignment_id');
            $table->string('nte_no')->nullable();
            $table->text('nte_attachment')->nullable();
            $table->string('issued_at')->nullable();
            $table->string('due_date')->nullable();
            $table->text('status')->nullable();
            $table->text('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_notice_to_explains');
    }
};
