<?php namespace AcDevelopers\LaravelFormProcessor\Command;

use Illuminate\Console\GeneratorCommand as GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class GenerateLaravelFormProcess
 * 
 * @package AcDevelopers\LaravelFormProcessor\Command
 */
class GenerateLaravelFormProcess extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:process {name} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Laravel Form Process class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Process';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('model')) {
            return __DIR__. '/../../stubs/laravelFormProcess.md.stub';
        } else {
            return __DIR__. '/../../stubs/laravelFormProcess.stub';
        }
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.config('laravel-form-processor.process-path');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replaceModel($stub, $this->option('model'));
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string  $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        $model = str_replace('/', '\\', $model);

        $stub = str_replace('DummyClass', $this->argument('name'), $stub);

        $stub = str_replace('NamespaceDummyModel', config('laravel-form-processor.model-path')
            .$this->option('model'), $stub);

        $stub = str_replace('DummyModel', $model, $stub);

        $stub = str_replace('DummyNamespace', config('laravel-form-processor.model-path'), $stub);

        $dummyModel = Str::camel($model) === 'user' ? 'model' : Str::camel($model);

        $stub = str_replace('dummyModel', $dummyModel, $stub);

        return $stub;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the processor applies to.'],
        ];
    }
}