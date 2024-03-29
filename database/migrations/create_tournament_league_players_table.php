<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_players')) {
            Schema::create('tournament_league_players', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('team_id')->nullable()->index();
                $table->unsignedBigInteger('player_role_id')->nullable()->index();
                $table->string('name')->index();
                $table->string('email')->nullable()->unique()->index();
                $table->string('slug')->nullable()->index();
                $table->string('nickname')->nullable()->index();
                $table->string('id_number')->nullable();
                $table->string('upload_image')->nullable();
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

        Schema::dropIfExists('tournament_league_players');
    }
};
