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
        Schema::create('_wdays', function (Blueprint $table) {
            $table->id();
            $table->string('weekday_name', 10); // Weekday name with max length of 10 characters
            // $table->boolean('is_weekend');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_wdays');
    }
};
