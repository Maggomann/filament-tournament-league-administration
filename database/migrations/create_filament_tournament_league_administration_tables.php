<?php

use Database\Seeders\FilamentTournamentProductionTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_league_calculation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->string('calculator')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_federations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calculation_type_id')->nullable()->index();
            $table->string('name')->index();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_leagues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('federation_id')->nullable()->index();
            $table->string('name')->index();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_id')->nullable()->index();
            $table->string('name')->index();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->string('name')->index();
            $table->string('email')->nullable()->unique()->index();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_game_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_schedule_id')->index();
            $table->unsignedInteger('day')->index();
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_game_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('federation_id')->nullable()->index();
            $table->morphs('gameschedulable', 'gameschedulable_index');
            $table->string('name')->index();
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('game_schedule_team', function (Blueprint $table) {
            $table->unsignedBigInteger('game_schedule_id')->nullable()->index('game_schedule_id_index');
            $table->unsignedBigInteger('team_id')->nullable()->index('team_id_index');
        });

        Schema::create('game_schedule_player', function (Blueprint $table) {
            $table->unsignedBigInteger('game_schedule_id')->nullable()->index('game_schedule_id_index');
            $table->unsignedBigInteger('player_id')->nullable()->index('player_id_index');
        });

        Schema::create('tournament_league_games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_schedule_id')->nullable()->index();
            $table->unsignedBigInteger('game_day_id')->nullable()->index();
            $table->unsignedBigInteger('home_team_id')->nullable()->index();
            $table->unsignedBigInteger('guest_team_id')->nullable()->index();
            $table->integer('home_points_legs')->default(0);
            $table->integer('guest_points_legs')->default(0);
            $table->integer('home_points_games')->default(0);
            $table->integer('guest_points_games')->default(0);
            $table->boolean('has_an_overtime')->default(false)->index();
            $table->integer('home_points_after_draw')->default(0);
            $table->integer('guest_points_after_draw')->default(0);
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        // TODO: Platzierung hinzufügen
        Schema::create('tournament_league_total_team_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_schedule_id')->nullable()->index();
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->integer('total_number_of_encounters')->default(0);
            $table->integer('total_wins')->default(0);
            $table->integer('total_defeats')->default(0);
            $table->integer('total_draws')->default(0);
            $table->integer('total_victory_after_defeats')->default(0);
            $table->integer('total_home_points_legs')->default(0);
            $table->integer('total_guest_points_legs')->default(0);
            $table->integer('total_home_points_games')->default(0);
            $table->integer('total_guest_points_games')->default(0);
            $table->integer('total_home_points_from_games_and_legs')->default(0);
            $table->integer('total_guest_points_from_games_and_legs')->default(0);
            $table->integer('total_points')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        collect([
            'tournament_league_modes',
            'tournament_league_dart_types',
            'tournament_league_qualification_levels',
        ])->each(function (string $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('title_translation_key');
                $table->timestamps();
                $table->softDeletes();
            });
        });

        Schema::create('tournament_league_free_tournaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mode_id')->nullable()->index();
            $table->unsignedBigInteger('dart_type_id')->nullable()->index();
            $table->unsignedBigInteger('qualification_level_id')->nullable()->index();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->string('slug')->nullable()->index();
            $table->integer('maximum_number_of_participants')->default(0);
            $table->integer('coin_money')->default(0);
            $table->string('prize_money_depending_on_placement');
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Artisan::call('db:seed', [
            '--class' => FilamentTournamentProductionTableSeeder::class,
        ]);

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
        if (! app()->environment('testing')) {
            return;
        }

        collect([
            'tournament_league_calculation_types',
            'tournament_league_game_days',
            'tournament_league_federations',
            'tournament_league_leagues',
            'tournament_league_teams',
            'tournament_league_players',
            'tournament_league_game_schedules',
            'game_schedule_team',
            'game_schedule_player',
            'tournament_league_total_team_points',
            'tournament_league_modes',
            'tournament_league_dart_types',
            'tournament_league_qualification_levels',
            'tournament_league_free_tournaments',
        ])->each(fn (string $tableName) => Schema::dropIfExists($tableName));
    }
};
