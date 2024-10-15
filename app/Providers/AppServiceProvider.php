<?php

namespace App\Providers;

use App\Contracts\DatabaseRepositoryInterface;
use App\Repositories\NoteRepository;
use App\Services\NoteService;
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
        /**
         * When inside NoteService
         *  we are using DatabaseRepositoryInterface as DI,
         *  we should load NoteRepository
         */
        $this->app->when(NoteService::class)
            ->needs(DatabaseRepositoryInterface::class)
            ->give(function () {
                return new NoteRepository();
            });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
