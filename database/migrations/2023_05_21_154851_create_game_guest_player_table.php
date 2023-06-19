<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('game_guest_player')) {
            Schema::create('game_guest_player', function (Blueprint $table) {
                $table->unsignedBigInteger('game_id')->nullable()->index();
                $table->unsignedBigInteger('player_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('game_guest_player');
    }
};
