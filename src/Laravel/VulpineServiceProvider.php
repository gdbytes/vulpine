<?php
namespace Vulpine\Laravel;

use Illuminate\Support\ServiceProvider;
use Vulpine\Services\Whmcs;

class VulpineServiceProvider extends ServiceProvider
{
    /**
     * The package name.
     *
     * @var string
     */
    protected $packageName = 'vulpine';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path($this->packageName . '.php'),
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
            __DIR__ . '/config.php', $this->packageName
        );

        $this->app->bind('whmcs', Whmcs::class);
    }
}