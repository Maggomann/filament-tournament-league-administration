<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_game_days')) {
            Schema::create('tournament_league_game_days', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('game_schedule_id')->index();
                $table->unsignedInteger('day')->index();
                $table->timestamp('started_at')->nullable()->index();
                $table->timestamp('ended_at')->nullable()->index();
                $table->timestamps();
                $table->softDeletes();
            });

            $this->addUniqueIndicesToGameDays();
        }
    }

    private function addUniqueIndicesToGameDays(): void
    {
        if (env('DB_CONNECTION') !== 'mysql') {
            return;
        }

        Schema::table('tournament_league_game_days', function (Blueprint $table) {
            $table->string('game_schedule_day_unique')
                ->virtualAs(
                    DB::raw(
                        "CONCAT(
                            CONCAT(day, '#', game_schedule_id),
                            '#',
                            IF(deleted_at IS NULL, '-',  deleted_at)
                        )"
                    )
                );
        });

        Schema::table('tournament_league_game_days', function (Blueprint $table) {
            $table->unique(['game_schedule_day_unique'], 'game_schedule_day_unique_index');
        });
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('tournament_league_game_days');
    }
};
