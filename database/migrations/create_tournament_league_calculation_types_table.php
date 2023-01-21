<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_calculation_types')) {
            Schema::create('tournament_league_calculation_types', function (Blueprint $table) {
                $table->id();
                $table->string('name')->index();
                $table->string('description')->nullable();
                $table->string('calculator')->index();
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

        Schema::dropIfExists('tournament_league_calculation_types');
    }
};
