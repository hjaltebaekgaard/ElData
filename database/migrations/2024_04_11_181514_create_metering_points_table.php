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
        Schema::create('metering_points', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id');

            $table->string('type');
            $table->string('sheet_id');
            $table->boolean('collect_enabled')->default(false);
            $table->boolean('transfer_enabled')->default(false);
            $table->dateTimeTz('latest_collect')->nullable();
            $table->dateTimeTz('latest_transfer')->nullable();

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metering_points');
    }
};
