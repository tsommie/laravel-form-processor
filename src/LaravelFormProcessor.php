<?php

namespace AcDevelopers\LaravelFormProcessor;

use AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessableInterface;
use AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessorInterface;
use AcDevelopers\LaravelFormProcessor\Exception\LaravelFormProcessException;
use Illuminate\Support\HtmlString;

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
     * @param LaravelFormProcessableInterface $processable
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     * @throws string
     */
    public function run(LaravelFormProcessableInterface $processable)
    {
        throw_unless($processable instanceof LaravelFormProcess, LaravelFormProcessException::class,
            class_basename($processable) . ' is not an instance of ' . LaravelFormProcess::class);

        return $processable->handle();
    }

    /**
     * Retrieve the process attached to the form request submitted and throw
     * and exception if it does not exist in the specified directory.
     *
     * @param string $_prKey
     * @return LaravelFormProcess|string
     * @throws \Throwable
     * @throws string
     */
    public function retrieveProcessFromFormField($_prKey)
    {
        $formProcess = decrypt($_prKey);

        throw_unless(class_exists($formProcess), LaravelFormProcessException::class,
            'This process does not exist: ' . $formProcess);

        return $formProcess;
    }

    /**
     * Render hidden input field for form process.
     *
     * @param string $processClassPath
     * @return HtmlString
     * @throws \Throwable
     * @throws string
     */
    public function renderProcess($processClassPath = '')
    {
        throw_if($processClassPath === '', LaravelFormProcessException::class, 'Expected a process class path, null given.');

        return new HtmlString('<input name="_prKey" value="'.encrypt($processClassPath).'" type="hidden"/>');
    }
}