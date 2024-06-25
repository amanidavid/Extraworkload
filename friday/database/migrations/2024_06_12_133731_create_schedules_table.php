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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wdays_id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('start_time_id');
            $table->unsignedBigInteger('end_time_id');

            // Foreign key constraints
            $table->foreign('wdays_id')->references('id')->on('_wdays')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->foreign('start_time_id')->references('id')->on('clocks')->onDelete('cascade');
            $table->foreign('end_time_id')->references('id')->on('clocks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
