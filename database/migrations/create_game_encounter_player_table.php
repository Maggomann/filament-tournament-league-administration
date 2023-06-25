<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('game_encounter_player')) {
            Schema::create('game_encounter_player', function (Blueprint $table) {
                $table->unsignedBigInteger('game_encounter_id')->nullable()->index();
                $table->unsignedBigInteger('player_id')->nullable()->index();
                $table->boolean('is_home')->default(false)->index();
                $table->boolean('is_guest')->default(false)->index();
            });
        }
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('game_encounter_player');
    }
};
