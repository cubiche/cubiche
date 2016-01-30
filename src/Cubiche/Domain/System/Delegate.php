<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @return static
     */
    public static function fromClosure(\Closure $closure)
    {
        return new static($closure);
    }

    /**
     * @param object $object
     * @param string $method
     *
     * @return static
     */
    public static function fromMethod($object, $method)
    {
        return new static(array($object, $method));
    }

    /**
     * @param string $function
     *
     * @return static
     */
    public static function fromFunction($function)
    {
        return new static($function);
    }

    /**
     * @param callable $callable
     */
    public function __construct($callable)
    {
        if (\is_object($callable) && ($callable instanceof \Closure)) {
            $this->closure = $callable;
        } elseif (\is_callable($callable)) {
            $this->closure = function () use ($callable) {
                return \call_user_func_array($callable, \func_get_args());
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
        return $this->invokeWith(\func_get_args());
    }

    /**
     * @param array $args
     *
     * @return mixed
     */
    public function invokeWith(array $args)
    {
        return \call_user_func_array($this->closure, $args);
    }
}
