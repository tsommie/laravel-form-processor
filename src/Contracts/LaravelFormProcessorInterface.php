<?php namespace AcDevelopers\LaravelFormProcessor\Contracts;

use AcDevelopers\LaravelFormProcessor\Exceptions\LaravelFormProcessorException;
use AcDevelopers\LaravelFormProcessor\LaravelFormProcess;

/**
 * Class LaravelFormProcessor
 * @package AcDevelopers\LaravelFormProcessor
 */
interface LaravelFormProcessorInterface
{
    /**
     * Run the process retrieved from the form submitted.
     *
     * @param LaravelFormProcessableInterface $laravelFormProcess
     * @return \Illuminate\Http\Response
     * @throws LaravelFormProcessorException
     */
    public static function run(LaravelFormProcessableInterface $laravelFormProcess);

    /**
     * Retrieve the process attached to the form request submitted.
     *
     * @param string $_prKey
     * @return LaravelFormProcess
     * @throws LaravelFormProcessorException
     */
    public static function retrieveProcessFromFormField($_prKey);

    /**
     * Process form field
     *
     * @param $processClassPath
     * @return string
     */
    public static function process($processClassPath);
}