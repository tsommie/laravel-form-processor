<?php

namespace AcDevelopers\LaravelFormProcessor;

use AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessableInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

/**
 * Class LaravelFormProcess
 *
 * @package AcDevelopers\LaravelFormProcessor
 */
abstract class LaravelFormProcess implements LaravelFormProcessableInterface
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Request
     */
    protected $request;

    /**
     * LaravelFormProcess constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}