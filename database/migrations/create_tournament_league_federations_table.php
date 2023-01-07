<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_federations')) {
            Schema::create('tournament_league_federations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('calculation_type_id')->nullable()->index();
                $table->string('name')->index();
                $table->string('slug')->nullable()->index();
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

        Schema::dropIfExists('tournament_league_federations');
    }
};
