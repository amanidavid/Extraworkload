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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ratiba_id');
            $table->unsignedBigInteger('enrollment_id');
            $table->timestamps();
            

            // Foreign key constraints
            $table->foreign('ratiba_id')->references('id')->on('ratibas')->onDelete('cascade');
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');
      
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
