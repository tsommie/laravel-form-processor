<?php

namespace AcDevelopers\LaravelFormProcessor\Contracts;

use Illuminate\Support\HtmlString;

/**
 * Class LaravelFormProcessor
 *
 * @package AcDevelopers\LaravelFormProcessor
 */
interface LaravelFormProcessorInterface
{
    /**
     * Run the process retrieved from the form submitted.
     *
     * @param LaravelFormProcessableInterface $processable
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     * @throws string
     */
    public function run(LaravelFormProcessableInterface $processable);

    /**
     * Retrieve the process attached to the form request submitted and throw
     * and exception if it does not exist in the specified directory.
     *
     * @param string $_prKey
     * @return \AcDevelopers\LaravelFormProcessor\LaravelFormProcess|string
     * @throws \Throwable
     * @throws string
     */
    public function retrieveProcessFromFormField($_prKey);

    /**
     * Render hidden input field for form process.
     *
     * @param string $processClassPath
     * @return HtmlString
     * @throws \Throwable
     * @throws string
     */
    public function renderProcess($processClassPath);
}