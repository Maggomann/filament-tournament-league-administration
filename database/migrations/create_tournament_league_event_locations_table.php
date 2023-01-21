<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_event_locations')) {
            Schema::create('tournament_league_event_locations', function (Blueprint $table) {
                $table->id();
                $table->string('name')->index();
                $table->timestamps();
                $table->softDeletes();
            });

            $this->addUniqueIndicesToEventLocations();
        }
    }

    private function addUniqueIndicesToEventLocations(): void
    {
        if (env('DB_CONNECTION') !== 'mysql') {
            return;
        }

        Schema::table('tournament_league_event_locations', function (Blueprint $table) {
            $table->string('name_unique')
                ->virtualAs(
                    DB::raw(
                        "CONCAT(
                            name,
                            '#',
                            IF(deleted_at IS NULL, '-',  deleted_at)
                        )"
                    )
                );
        });

        Schema::table('tournament_league_event_locations', function (Blueprint $table) {
            $table->unique(['name_unique'], 'name_unique_index');
        });
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('tournament_league_event_locations');
    }
};
