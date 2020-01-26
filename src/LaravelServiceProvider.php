<?php

namespace Orchestra\Canvas\Core;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider implements DeferrableProvider
{
    use CommandsProvider;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Presets\Preset::class, function (Container $app) {
            return $this->presetForLaravel($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Presets\Preset::class,
        ];
    }
}
