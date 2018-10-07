<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot()
    {
        \View::composer('partials._navbar', function (View $view) {
            $categories = Category::query()
                ->whereNull('parent_id')
                ->with(['subcategories'])
                ->get()
            ;

            $view->with('categories', $categories);
        });
    }
}
