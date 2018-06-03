<?php

namespace AcDevelopers\LaravelFormProcessor\Console\Commands;

use Illuminate\Console\GeneratorCommand as LaravelGeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MakeProcessCommand
 *
 * @package AcDevelopers\LaravelFormProcessor\Console\Commands
 */
class MakeProcessCommand extends LaravelGeneratorCommand
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
    protected $description = 'Create a new process class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'process';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('model')) {
            return __DIR__.'/../stubs/model.process.stub';
        }

        return __DIR__.'/../stubs/process.stub';
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

        $model = $this->option('model');

        return $model ? $this->replaceModel($stub, $model) : $stub;
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

        $namespaceModel = config('laravel-form-processor.namespaces.model').$model;

        if (Str::startsWith($model, '\\')) {
            $stub = str_replace('NamespacedDummyModel', trim($model, '\\'), $stub);
        } else {
            $stub = str_replace('NamespacedDummyModel', $namespaceModel, $stub);
        }

        $stub = str_replace(
            "use {$namespaceModel};\nuse {$namespaceModel};", "use {$namespaceModel};", $stub
        );

        $model = class_basename(trim($model, '\\'));

        $dummyUser = class_basename(config('auth.providers.users.model'));

        $stub = str_replace('DocDummyModel', Str::snake($model, ' '), $stub);

        $stub = str_replace('DummyModel', $model, $stub);

        $stub = str_replace('dummyModel', Str::camel($model), $stub);

        $stub = str_replace('DummyUser', $dummyUser, $stub);

        return str_replace('DocDummyPluralModel', Str::snake(Str::plural($model), ' '), $stub);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('laravel-form-processor.namespaces.process');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the repository applies to.'],
        ];
    }
}
