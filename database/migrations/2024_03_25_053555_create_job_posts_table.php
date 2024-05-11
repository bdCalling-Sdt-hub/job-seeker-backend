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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruiter_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('job_title');
            $table->date('application_last_date');
            $table->text('salary');
            $table->text('job_type');
            $table->text('work_type');
            $table->text('work_shift');
            $table->integer('category_id');
            $table->text('area');
            $table->text('education');
            $table->text('experience');
            $table->text('additional_requirement');
            $table->text('responsibilities');
            $table->text('compensation_other_benifits');
            $table->text('vacancy');
            $table->string('status')->default('pending');
            $table->integer('package_id')->nullable();
            $table->integer('subscription_id');
            $table->json('key_word');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
