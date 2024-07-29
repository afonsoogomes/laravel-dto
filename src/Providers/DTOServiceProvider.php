<?php

namespace AfonsoOGomes\LaravelDTO\Providers;

use Illuminate\Support\ServiceProvider;
use AfonsoOGomes\LaravelDTO\Console\Commands\MakeDTOCommand;

class DTOServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            MakeDTOCommand::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Optionally, publish the stub file
        $this->publishes([
            __DIR__.'/../../stubs/dto.plain.stub' => base_path('stubs/dto.plain.stub'),
        ]);
    }
}
