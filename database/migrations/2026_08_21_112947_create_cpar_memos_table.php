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
        Schema::create('cpar_memos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cpar_id')->nullable();
            $table->string('result_id')->nullable();
            $table->string('assignment_id')->nullable();
            $table->string('memo_no')->unique();
            $table->string('memo_date');
            $table->string('subject')->nullable();
            $table->longText('memo_content')->nullable();
            $table->text('memo_attachment')->nullable();
            $table->string('status')->default('DRAFT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_memos');
    }
};
