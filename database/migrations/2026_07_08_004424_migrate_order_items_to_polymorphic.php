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
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('game_type')->nullable()->after('mame_id');
            $table->unsignedBigInteger('game_id')->nullable()->after('game_type');
        });

        // Migrate existing order items
        $orderItems = DB::table('order_items')->get();
        foreach ($orderItems as $item) {
            // Find the mame record
            $mame = DB::table('mame')->where('id', $item->mame_id)->first();
            if ($mame) {
                // Find corresponding arcade_game
                $arcadeGame = DB::table('arcade_games')->where('rom', $mame->rom)->first();
                if ($arcadeGame) {
                    DB::table('order_items')->where('id', $item->id)->update([
                        'game_type' => \App\Models\ArcadeGame::class,
                        'game_id' => $arcadeGame->id,
                    ]);
                }
            }
        }

        Schema::table('order_items', function (Blueprint $table) {
            // Drop mame_id foreign constraint if it exists
            $table->dropForeign(['mame_id']);
            $table->dropColumn('mame_id');
            
            // Make new columns required
            $table->string('game_type')->nullable(false)->change();
            $table->unsignedBigInteger('game_id')->nullable(false)->change();
            
            $table->index(['game_type', 'game_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('mame_id')->nullable()->constrained('mame')->onDelete('cascade');
            $table->dropIndex(['game_type', 'game_id']);
            $table->dropColumn(['game_type', 'game_id']);
        });
    }
};
