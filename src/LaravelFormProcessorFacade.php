<?php

namespace AcDevelopers\LaravelFormProcessor;

use AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessableInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\HtmlString;

/**
 * Class LaravelFormProcessorFacade
 *
 * @method static Response run(LaravelFormProcessableInterface $processable)
 * @method static LaravelFormProcess|string retrieveProcessFromFormField($_prKey)
 * @method static HtmlString renderProcess($processClassPath)
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
        return 'formProcessor';
    }
}