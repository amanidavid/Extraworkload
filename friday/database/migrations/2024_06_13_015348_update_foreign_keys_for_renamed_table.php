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
        //
        // Drop the existing foreign key constraint
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['wdays_id']);
        });

        // Rename the table
        // Schema::rename('wdays', 'seasons');

        // Add the new foreign key constraint
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('wdays_id')->references('id')->on('seasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
