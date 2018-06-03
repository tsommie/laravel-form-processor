<?php

namespace AcDevelopers\LaravelFormProcessor;

use AcDevelopers\LaravelFormProcessor\Console\Commands\MakeProcessCommand;
use AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessorInterface;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class LaravelFormProcessorServiceProvider
 * 
 * @package AcDevelopers\LaravelFormProcessor
 */
class LaravelFormProcessorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-form-processor.php'),
            ], 'config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('laravel-form-processor');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeProcessCommand::class
            ]);
        }

        Blade::directive('renderProcess', function ($processClassPath) {
            return "<?php print '" . app('formProcessor')->renderProcess($processClassPath) . "' ?>";
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'laravel-form-processor'
        );

        $this->app->singleton('formProcessor', function () {
            return new LaravelFormProcessor;
        });

        $this->app->bind(LaravelFormProcessorInterface::class, LaravelFormProcessor::class);
    }
}
