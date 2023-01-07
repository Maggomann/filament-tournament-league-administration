<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_free_tournaments')) {
            Schema::create('tournament_league_free_tournaments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('mode_id')->nullable()->index();
                $table->unsignedBigInteger('dart_type_id')->nullable()->index();
                $table->unsignedBigInteger('qualification_level_id')->nullable()->index();
                $table->string('name')->index();
                $table->string('slug')->nullable()->index();
                $table->text('description')->nullable();
                $table->integer('maximum_number_of_participants')->default(0);
                $table->integer('coin_money')->default(0);
                $table->json('prize_money_depending_on_placement')->nullable();
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

        Schema::dropIfExists('tournament_league_free_tournaments');
    }
};
