<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applies', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('job_post_id');
            $table->integer('category_id')->nullable();
            $table->integer('experience')->nullable();
            $table->text('interest')->nullable();
            $table->string('cv')->nullable();
            $table->text('salary');
            $table->text('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applies');
    }
};
