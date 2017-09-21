<?php
namespace Vulpine;

use Illuminate\Support\ServiceProvider;

class VulpineServiceProvider extends ServiceProvider
{
    /**
     * The package name.
     *
     * @var string
     */
    private $packageName = 'vulpine';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/' . $this->packageName .'.php' => config_path($this->packageName . '.php'),
        ], 'config');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/' . $this->packageName .'.php', $this->packageName
        );

        $this->app->bind('whmcs', Whmcs::class);
    }
}