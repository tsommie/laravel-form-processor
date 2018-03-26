<?php namespace AcDevelopers\LaravelFormProcessor;

use AcDevelopers\LaravelFormProcessor\Command\GenerateLaravelFormProcess;
use AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessorInterface;
use Illuminate\Support\Facades\Blade as LaravelBlade;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class LaravelFormProcessorServiceProvider
 * 
 * @package AcDevelopers\LaravelFormProcessor
 */
class LaravelFormProcessorServiceProvider extends  LaravelServiceProvider
{
    /**
     * Path to the config file
     *
     * @var string
     */
    protected $configPath = __DIR__ . '/../config/config.php';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication) {
            $this->publishes([
                $this->configPath => config_path('laravel-form-processor.php')
            ], 'config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('laravel-form-processor');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateLaravelFormProcess::class
            ]);
        }

        $this->app->bind('LaravelFormProcessor', function (){
            return new LaravelFormProcessor;
        });

        $this->handleBladeDirective();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath, 'laravel-form-processor');

        $this->app->bind(LaravelFormProcessorInterface::class, LaravelFormProcessor::class);
    }

    /**
     * Process form field
     */
    private function handleBladeDirective()
    {
        LaravelBlade::directive('process', function ($processClassPath) {
            return '<?php print \'<input type="text" name="_prKey" value="' . encrypt($processClassPath) . '" type="hidden"/>\' ?>';
        });
    }
}