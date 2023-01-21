<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('game_schedule_player')) {
            Schema::create('game_schedule_player', function (Blueprint $table) {
                $table->unsignedBigInteger('game_schedule_id')->nullable()->index('gsp_game_schedule_id_index');
                $table->unsignedBigInteger('player_id')->nullable()->index('gsp_player_id_index');
            });
        }
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('game_schedule_player');
    }
};
