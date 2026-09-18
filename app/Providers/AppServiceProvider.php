<?php

declare(strict_types=1);

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

        TallStackUi::customize()
            ->tab()
            ->block('base.wrapper', 'dark:bg-darkcontentbg dark:border-darkborder border w-full rounded-lg bg-white shadow-sm')
            ->block('base.divider', 'h-px border-0 bg-gray-300 dark:bg-darkborder')
            ->block('item.select', 'text-primary-500 dark:text-white border-primary-500 dark:border-white group inline-flex cursor-pointer items-center border-b-2 font-medium')
            ->block('item.unselect', 'dark:text-gray-400 cursor-pointer border-b-2 border-transparent font-medium text-gray-500 flex hover:text-gray-700 dark:hover:text-gray-200');

        TallStackUi::customize()
            ->form('date')
            ->block('floating.default', 'dark:bg-darkcontentbg border-dark-200 dark:border-darkborder absolute !z-[900] rounded-lg border bg-white')
            ->block('box.picker.button', 'text-gray-900 focus:ring-darkborder flex items-center justify-between rounded-lg px-2 py-1 mb-6 text-sm font-semibold focus:outline-hidden focus:ring-2 dark:text-white')
            ->block('box.picker.wrapper.first', 'dark:bg-darkcontentbg absolute left-0 top-0 flex h-full w-full select-none rounded-lg bg-white p-3')
            ->block('box.picker.label', 'text-gray-900 dark:bg-darkcontentbg dark:text-darkcontenttext hover:bg-dark-100 dark:hover:bg-darkinputcolor focus:ring-darkborder flex cursor-pointer items-center justify-between rounded-lg bg-white px-2 py-1 text-sm font-semibold focus:outline-hidden focus:ring-0 dark:text-white')
            ->block('box.picker.range', 'text-gray-400 dark:text-darksmalltext font-medium hover:bg-dark-100 dark:hover:bg-darkinputcolor text-gray-600 dark:text-darksmalltext disabled:text-gray-400 dark:disabled:text-dark-500 flex h-6 w-1/4 cursor-pointer select-none items-center justify-center rounded-md p-1 text-center font-normal disabled:cursor-not-allowed')
            ->block('button.day', 'focus:shadow-outline disabled:text-gray-400 dark:disabled:text-dark-500 dark:active:bg-darkaddbutton ring-darkaddbutton active:bg-darkaddbutton dark:text-darkcontenttext flex h-7 w-7 items-center justify-center rounded-full text-center text-sm leading-none outline-hidden transition-all duration-200 ease-in-out hover:shadow-sm active:text-white disabled:cursor-not-allowed cursor-pointer')
            ->block('button.select', 'text-gray-600 dark:text-darkcontenttext hover:bg-darkinputcolor dark:hover:bg-darkinputcolor')
            ->block('button.today', 'text-darkaddbutton dark:text-darkaddbutton !font-bold')
            ->block('button.selected', 'bg-darkaddbutton !text-white hover:bg-darkaddbuttonhover')
            ->block('button.helpers', 'text-gray-500 dark:text-darkcontenttext bg-darkinputcolor dark:bg-darkinputcolor hover:bg-darkborder dark:hover:bg-darkborder select-none whitespace-nowrap rounded-md px-2 py-1 text-sm font-medium')
            ->block('button.navigate', 'focus:shadow-outline hover:bg-darkinputcolor dark:hover:bg-darkinputcolor inline-flex cursor-pointer rounded-full p-1 transition duration-100 ease-in-out focus:outline-hidden')
            ->block('range', 'bg-darkinputcolor dark:bg-darkinputcolor');

        $this->configureDefaults();

        Gate::define('admin', function(User $user) {
            return in_array($user->role, ['administrateur']);
        });
        Gate::define('view-suivi', function(User $user) {
            return in_array($user->role, ['professeur', 'administrateur', 'secretaire']);
        });
        Gate::define('view-absence', function(User $user) {
            return in_array($user->role, ['professeur', 'administrateur', 'secretaire']);
        });

        Gate::define('sec', function(User $user) {
            return in_array($user->role, ['administrateur', 'secretaire']);
        });

        Gate::define('prof', function(User $user) {
            return $user->role === 'professeur';
        });

        Gate::define('view-notes', function(User $user) {
            return in_array($user->role, ['administrateur', 'secretaire', 'professeur']);
        });

        Gate::define('view-etudiants', function(User $user) {
            return in_array($user->role, ['administrateur', 'secretaire']);
        });
        Gate::define('view-programme', function(User $user) {
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

        Password::defaults(
            fn (): ?Password => app()->isProduction()
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
