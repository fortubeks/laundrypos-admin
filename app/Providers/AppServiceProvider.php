<?php
namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $command         = $_SERVER['argv'][1] ?? null;
            $blockedCommands = [
                'migrate',
                'migrate:fresh',
                'migrate:refresh',
                'migrate:reset',
                'migrate:rollback',
                'db:wipe',
                'schema:dump',
            ];

            $allowAdminMigrations = filter_var(env('ALLOW_ADMIN_MIGRATIONS', false), FILTER_VALIDATE_BOOLEAN);

            if ($command && in_array($command, $blockedCommands, true) && ! $allowAdminMigrations) {
                fwrite(STDERR, "\nMigration commands are disabled in the admin project.\n");
                fwrite(STDERR, "Run migrations from the laundrypos backend instead.\n");
                exit(1);
            }
        }

        Paginator::useBootstrap();
    }
}
