<?php

namespace App\Providers;

use App\Models\Pengaturan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Pastikan tabel 'pengaturans' sudah ada sebelum diakses
        if (Schema::hasTable('pengaturans')) {
            $pengaturan = Pengaturan::first();
            View::share('pengaturan', $pengaturan);
        }
    }
}
