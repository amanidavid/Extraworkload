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
        Schema::create('lecture_ins_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('lecturerlevels_id');
            $table->timestamps();
            

            // Foreign key constraints
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('lecturerlevels_id')->references('id')->on('lecturerlevels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_ins_tables');
    }
};
