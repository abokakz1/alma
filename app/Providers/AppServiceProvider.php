<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $locale = App::getLocale();
        $columns = [
                "order",
                "name_{$locale} as name",
                "parent_id",
                "menu_id",
                "url",
                "id",
            ];
        $footers = DB::table('menu_items')->select($columns)->where('menu_id', 1)->orderBy('order')->get();
        $footers = collect($footers);
        $main = $footers->filter(function ($value, $key) {
            return !$value->parent_id;
        });
        view()->share('footer_data', ['footers' => $footers, 'main_footers' => $main]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
