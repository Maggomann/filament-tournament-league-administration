<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_teams')) {
            Schema::create('tournament_league_teams', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('league_id')->nullable()->index();
                $table->string('name')->index();
                $table->string('slug')->nullable()->index();
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

        Schema::dropIfExists('tournament_league_teams');
    }
};
