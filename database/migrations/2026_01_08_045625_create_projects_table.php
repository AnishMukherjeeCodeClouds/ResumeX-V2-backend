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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('description');
            $table->json('technologies');
            $table->string('liveLink')->nullable();
            $table->string('githubLink')->nullable();
            $table->date('startDate');
            $table->date('endDate')->nullable();

            $table->foreignUuid('resumeId')->references('id')->on('resumes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
