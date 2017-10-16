<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\Generate;

class CommandServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.generate.crud', function()
        {
            return new Generate;
        });

        $this->commands(
            'command.generate.crud'
        );
    }
}