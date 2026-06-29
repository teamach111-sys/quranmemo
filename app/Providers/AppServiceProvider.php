<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();

        \Illuminate\Support\Facades\Gate::define('admin', function (\App\Models\User $user) {
            return in_array($user->role, ['administrateur']);
        });

        \Illuminate\Support\Facades\Gate::define('sec', function (\App\Models\User $user) {
            return in_array($user->role, ['administrateur', 'secretaire']);
        });

        \Illuminate\Support\Facades\Gate::define('prof', function (\App\Models\User $user) {
            return $user->role === 'professeur';
        });

        \Illuminate\Support\Facades\Gate::define('view-notes', function (\App\Models\User $user) {
            return in_array($user->role, ['administrateur', 'secretaire', 'professeur']);
        });

        \Illuminate\Support\Facades\Gate::define('view-etudiants', function (\App\Models\User $user) {
            return in_array($user->role, ['administrateur', 'secretaire']);
        });
         \Illuminate\Support\Facades\Gate::define('view-programme', function (\App\Models\User $user) {
            return in_array($user->role, ['administrateur', 'secretaire']);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
