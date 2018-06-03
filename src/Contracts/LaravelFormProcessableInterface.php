<?php

namespace AcDevelopers\LaravelFormProcessor\Contracts;

/**
 * Class LaravelFormProcess
 *
 * @package AcDevelopers\LaravelFormProcessor
 */
interface LaravelFormProcessableInterface
{
    /**
     * Handle form processing
     *
     * @return \Illuminate\Http\Response
     */
    public function handle();
}