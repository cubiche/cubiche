<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System;

/**
 * Delegate.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Delegate
{
    /**
     * @var \Closure
     */
    protected $closure;

    /**
     * @param Closure $closure
     *
     * @return Delegate
     */
    public static function fromClosure(\Closure $closure)
    {
        return new static($closure);
    }

    /**
     * @param object $object
     * @param string $method
     *
     * @return Delegate
     */
    public static function fromMethod($object, $method)
    {
        return new static(array($object, $method));
    }

    /**
     * @param string $function
     *
     * @return Delegate
     */
    public static function fromFunction($function)
    {
        return new static($function);
    }

    /**
     * @param callable $function
     */
    public function __construct($function)
    {
        if (\is_object($function) && ($function instanceof \Closure)) {
            $this->closure = $function;
        } elseif (\is_callable($function)) {
            $this->closure = function () use ($function) {
                return \call_user_func_array($function, \func_get_args());
            };
        } else {
            throw new \InvalidArgumentException('The function must be a \Closure instance or a callable.');
        }
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return \call_user_func_array($this->closure, \func_get_args());
    }
}
