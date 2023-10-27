<?php

namespace Grilar\Optimize\Facades;

use Grilar\Optimize\Supports\Optimizer;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isEnabled()
 * @method static \Grilar\Optimize\Supports\Optimizer enable()
 * @method static \Grilar\Optimize\Supports\Optimizer disable()
 *
 * @see \Grilar\Optimize\Supports\Optimizer
 */
class OptimizerHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Optimizer::class;
    }
}
