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
        Schema::create('mame', function (Blueprint $table) {
            $table->id();
            $table->string('rom', 100)->unique()->index();
            $table->string('full_name', 255)->nullable();
            $table->string('driver', 100)->nullable()->index();
            $table->string('year', 20)->nullable();
            $table->string('manufacturer', 150)->nullable();
            $table->string('romof', 100)->nullable()->index();
            $table->string('cloneof', 100)->nullable()->index();
            $table->boolean('use_bios')->default(false);
            $table->boolean('use_chds')->default(false);
            $table->string('display_rotate', 20)->nullable();
            $table->integer('display_width')->nullable();
            $table->integer('display_height')->nullable();
            $table->string('display_orientation', 50)->nullable();
            $table->string('sourcefile', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mame');
    }
};
