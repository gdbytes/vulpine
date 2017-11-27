<?php
namespace Vulpine\Laravel;

use Illuminate\Support\ServiceProvider;
use Vulpine\Whmcs;

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
            dirname(__DIR__ ) . '/config/' . $this->packageName .'.php' => config_path($this->packageName . '.php'),
        ], 'config');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__ ) . '/config/' . $this->packageName .'.php', $this->packageName
        );

        $this->app->singleton(Whmcs::class, function() {
            return new Whmcs();
        });

        $this->app->alias(Whmcs::class, 'whmcs');
    }
}