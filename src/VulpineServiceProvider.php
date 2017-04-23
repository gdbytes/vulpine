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
    protected $packageName = 'vulpine';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/vulpine.php' => config_path($this->packageName . '.php'),
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
            __DIR__ . '/../config/vulpine.php', $this->packageName
        );
    }
}