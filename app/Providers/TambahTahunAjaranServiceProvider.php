<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\TahunAjaran;

class TambahTahunAjaranServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $tahunAjaranAktif = TahunAjaran::getActiveTahunAjaran();

        View::share('tahunAjaranAktif', $tahunAjaranAktif);
    }
}
