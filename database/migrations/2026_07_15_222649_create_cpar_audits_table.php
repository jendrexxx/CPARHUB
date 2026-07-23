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
        Schema::create('cpar_audits', function (Blueprint $table) {
            $table->id();
            $table->integer('cpar_id');
            $table->integer('head_id');
            $table->text('audit_comments');
            $table->text('audit_recommendation');
            $table->string('audit_risk');
            $table->string('audit_solution');
            $table->integer('audit_probability');
            $table->integer('audit_impact');
            $table->integer('audit_risk_result');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpar_audits');
    }
};
