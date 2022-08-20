<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tournament_league_federations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });

        Schema::create('tournament_league_leagues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });
    }
};
