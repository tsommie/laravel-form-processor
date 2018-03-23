<?php namespace AcDevelopers\LaravelFormProcessor;

use AcDevelopers\LaravelFormProcessor\Contracts\LaravelFormProcessableInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Class LaravelFormProcess
 * 
 * @package AcDevelopers\LaravelFormProcessor
 */
abstract class LaravelFormProcess implements LaravelFormProcessableInterface
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Handle form processing
     *
     * @return \Illuminate\Http\Response
     */
    abstract public function handle();
}