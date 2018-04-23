<?php
/**
 * Part of the evias/nem-php-examples package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    evias/nem-php-examples
 * @version    1.0.0
 * @author     Grégory Saive <greg@evias.be>
 * @license    MIT License
 * @copyright  (c) 2017-2018, Grégory Saive <greg@evias.be>
 * @link       http://github.com/evias/nem-php-examples
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191); 
        $this->setupConfig();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            "nem.network.config",
        ];
    }

    /**
     * Setup the NEM blockchain config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../../config') . "/nem.php";
        if (! $this->isLumen())
            // console laravel, use config_path helper
            $this->publishes([$source => config_path('nem.php')]);
        else
            // lumen configure app
            $this->app->configure('nem.network.config');

        $this->mergeConfigFrom($source, 'nem.network.config');
    }

    /**
     * Register Twig config option bindings.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->app->bindIf('nem.network.config', function () {
            return $this->app['config']->get('nem.network.config');
        }, true);
    }

    /**
     * Check if we are running Lumen or not.
     *
     * @return bool
     */
    protected function isLumen()
    {
        return strpos($this->app->version(), 'Lumen') !== false;
    }

    /**
     * Check if we are running on PHP 7.
     *
     * @return bool
     */
    protected function isRunningPhp7()
    {
        return version_compare(PHP_VERSION, '7.0-dev', '>=');
    }
}
