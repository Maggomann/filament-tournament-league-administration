<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_game_schedules')) {
            Schema::create('tournament_league_game_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('federation_id')->nullable()->index();
                $table->unsignedBigInteger('league_id')->nullable()->index();
                $table->string('name')->index();
                $table->timestamp('started_at')->nullable()->index();
                $table->timestamp('ended_at')->nullable()->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('tournament_league_game_schedules');
    }
};
