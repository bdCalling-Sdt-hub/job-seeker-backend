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
        Schema::create('emplyer_contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('sender_id');
            $table->integer('reciver_id');
            $table->text('body');
            $table->text('attacment')->nullable();
            $table->text('seen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emplyer_contacts');
    }
};
