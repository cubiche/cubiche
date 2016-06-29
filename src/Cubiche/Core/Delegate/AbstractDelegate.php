<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Delegate;

/**
 * Abstract Delegate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class AbstractDelegate implements CallableInterface
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param callable $callable
     */
    public function __construct($callable)
    {
        if (!\is_callable($callable)) {
            throw new \InvalidArgumentException('The $callable argument must be a callable.');
        }

        if (\is_string($callable) && \strpos($callable, '::')) {
            $callable = explode('::', $callable);
        }

        $this->callable = $callable;
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
        return \call_user_func_array($this->callable, $args);
    }
}
