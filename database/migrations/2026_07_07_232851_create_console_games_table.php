<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('console_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_platform_id')->constrained()->cascadeOnDelete();
            $table->string('rom')->index();
            $table->string('title')->nullable();
            $table->decimal('size_mb', 10, 2)->default(0);
            $table->string('release_date')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['sub_platform_id', 'rom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('console_games');
    }
};
