<?php namespace AcDevelopers\LaravelFormProcessor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class LaravelFormProcessorFacade
 *
 * @method static \Illuminate\Http\Response run(\AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessableInterface $laravelFormProcess)
 * @method static LaravelFormProcess retrieveProcessFromFormField($_prKey)
 * @method static string process($processClassPath)
 *
 * @package AcDevelopers\LaravelFormProcessor
 */
class LaravelFormProcessorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LaravelFormProcessor';
    }
}