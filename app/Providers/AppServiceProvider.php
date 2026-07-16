<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use TallStackUi\Facades\TallStackUi;

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

        TallStackUi::customize()
            ->modal()
            ->block('wrapper.first', 'fixed inset-0 bg-overlayy transform transition-opacity')
            ->block('wrapper.third', 'mx-auto flex min-h-full w-full transform justify-center sm:p-4 !max-w-none')
            ->block('wrapper.fourth', 'dark:bg-darkcontentbg relative flex w-full sm:w-auto !max-w-none transform flex-col rounded-t-xl sm:rounded-xl bg-white text-left shadow-xl transition-all');

        TallStackUi::customize()
            ->form('upload')
            ->block('floating.class', 'p-3 min-w-[300px] max-w-[350px]')
            ->block('floating.default', 'dark:bg-darkcontentbg border-dark-200 dark:border-dark-600 absolute !z-[900] rounded-lg border bg-white')
            ->and
            ->form('upload')
            ->block('placeholder.wrapper', 'dark:border-darkinputcolor dark:bg-darkinputcolor relative flex h-20 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 transition');

        TallStackUi::customize()
            ->form('input')
            ->block('input.base', 'dark:placeholder-dark-400 w-full rounded-md border-0 bg-transparent py-3 ring-0 placeholder:text-gray-400 focus:outline-hidden focus:ring-transparent sm:text-sm sm:leading-6')
            ->and
            ->form('input')
            ->block('input.color.base', 'dark:ring-dark-600 dark:text-dark-300 text-gray-600 ring-gray-300')
            ->and
            ->select()
            ->block('input.base', 'dark:placeholder-dark-400 w-full rounded-md border-0 bg-transparent py-3 ring-0 placeholder:text-gray-400 focus:outline-hidden focus:ring-transparent sm:text-sm sm:leading-6')
            ->and
            ->form('input')
            ->block('input.wrapper', 'focus:ring-primary-600 focus-within:focus:ring-primary-600 focus-within:ring-primary-600 dark:focus-within:ring-darkinputoutline flex rounded-md ring-1 focus-within:ring-2')
            ->and
            ->table()
            ->block('table.thead.normal', 'bg-gray-50 dark:bg-darkcontentbg')
            ->and
            ->table()
            ->block('table.tbody', 'dark:bg-darkcontentbg dark:divide-dark-500/20 divide-y divide-gray-200 bg-white')
            ->and
            ->select()
            ->block('input.wrapper', 'focus:ring-darkinputoutline focus-within:focus:ring-darkinputoutline focus-within:ring-darkinputoutline dark:focus-within:ring-darkinputoutline flex rounded-md ring-1 focus-within:ring-2');

        TallStackUi::customize()
            ->button()
            ->block('wrapper.sizes.md', 'text-md px-5 py-3');





        $this->configureDefaults();

        Gate::define('admin', function (User $user) {
            return in_array($user->role, ['administrateur']);
        });

        Gate::define('sec', function (User $user) {
            return in_array($user->role, ['administrateur', 'secretaire']);
        });

        Gate::define('prof', function (User $user) {
            return $user->role === 'professeur';
        });

        Gate::define('view-notes', function (User $user) {
            return in_array($user->role, ['administrateur', 'secretaire', 'professeur']);
        });

        Gate::define('view-etudiants', function (User $user) {
            return in_array($user->role, ['administrateur', 'secretaire']);
        });
        Gate::define('view-programme', function (User $user) {
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
