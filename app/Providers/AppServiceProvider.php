<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Barang;

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

        // Tambahkan ini untuk mencegah error pembacaan font macro di framework
        \Illuminate\Foundation\Vite::macro('fonts', function () {
            return '';
        });

        View::composer(['layouts.inventory', 'layouts.app'], function ($view) {

            $stokMenipisList = Barang::whereColumn(
                'stok_sekarang',
                '<=',
                'stok_minimal'
            )->get();

            $view->with([
                'stokMenipisList' => $stokMenipisList,
                'totalWarning' => $stokMenipisList->count(),
            ]);
        });
    }
}
