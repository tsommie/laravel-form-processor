<?php namespace AcDevelopers\LaravelFormProcessor;

use AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessableInterface;
use AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessorInterface;
use AcDevelopers\LaravelFormProcessor\Exceptions\LaravelFormProcessorException;

/**
 * Class LaravelFormProcessor
 * 
 * @package AcDevelopers\LaravelFormProcessor
 */
class LaravelFormProcessor implements LaravelFormProcessorInterface
{
    /**
     * Run the process retrieved from the form submitted.
     *
     * @param LaravelFormProcessableInterface $laravelFormProcess
     * @return \Illuminate\Http\Response
     * @throws LaravelFormProcessorException
     */
    public static function run(LaravelFormProcessableInterface $laravelFormProcess)
    {
        if ($laravelFormProcess instanceof LaravelFormProcess) {
            return $laravelFormProcess->handle();
        }

        throw new LaravelFormProcessorException($laravelFormProcess . ' is not an instance of ' . LaravelFormProcess::class);
    }

    /**
     * Retrieve the process attached to the form request submitted.
     *
     * @param string $_prKey
     * @return LaravelFormProcess
     * @throws LaravelFormProcessorException
     */
    public static function retrieveProcessFromFormField($_prKey)
    {
        $laravelFormProcess = decrypt($_prKey);

        if (! class_exists($laravelFormProcess)) {
            throw new LaravelFormProcessorException('This process does not exist: ' . $laravelFormProcess);
        }

        return $laravelFormProcess;
    }

    /**
     * Process form field
     *
     * @param $processClassPath
     * @return string
     */
    public static function process($processClassPath)
    {
        return '<input name="_prKey" value="' . encrypt($processClassPath) . '" type="hidden"/>';
    }
}