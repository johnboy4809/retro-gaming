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
        Schema::create('snes', function (Blueprint $table) {
            $table->id();
            $table->string('rom', 255)->index();
            $table->string('region', 50)->nullable()->index();
            $table->string('release_date', 20)->nullable();
            $table->decimal('size_mb', 8, 2)->nullable();
            $table->string('crc32', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snes');
    }
};
