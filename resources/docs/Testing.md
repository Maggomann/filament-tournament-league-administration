# Dartverein testen

php artisan addressable:install
php artisan filament-tournament-league-administration:install
php artisan migrate:fresh --seed
php artisan db:seed --class=FilamentTournamentTableSeeder
