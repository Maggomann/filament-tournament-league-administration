<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('game_schedule_team')) {
            Schema::create('game_schedule_team', function (Blueprint $table) {
                $table->unsignedBigInteger('game_schedule_id')->nullable()->index('game_schedule_id_index');
                $table->unsignedBigInteger('team_id')->nullable()->index('team_id_index');
            });
        }
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('game_schedule_team');
    }
};
