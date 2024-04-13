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
        Schema::create('data_points', function (Blueprint $table) {
            $table->string('metering_point_id');
            $table->foreign('metering_point_id')->references('id')->on('metering_points')->cascadeOnDelete();
            $table->decimal('quantity', 6, 3);
            $table->string('quality');
            $table->dateTimeTz('time_start');

            $table->unique(['metering_point_id', 'time_start'], 'id');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_points');
    }
};
