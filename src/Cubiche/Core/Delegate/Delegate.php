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
 * Delegate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Delegate extends AbstractCallable
{
    /**
     * @var \ReflectionFunction|\ReflectionMethod
     */
    private $reflection = null;

    /**
     * @var callable
     */
    protected $callable;

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
     * @param string $class
     * @param string $method
     *
     * @return static
     */
    public static function fromStaticMethod($class, $method)
    {
        return new static(array($class, $method));
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
        if (!\is_callable($callable)) {
            throw new \InvalidArgumentException('The $callable argument must be a callable.');
        }

        if (\is_string($callable) && \strpos($callable, '::')) {
            $callable = explode('::', $callable);
        }

        $this->callable = $callable;
    }

    /**
     * @return \ReflectionFunction|\ReflectionMethod
     */
    public function reflection()
    {
        if ($this->reflection === null) {
            if (\is_array($this->callable)) {
                $this->reflection = new \ReflectionMethod($this->callable[0], $this->callable[1]);
            } elseif (\is_object($this->callable) && !$this->callable instanceof \Closure) {
                $this->reflection = new \ReflectionMethod($this->callable, '__invoke');
            } else {
                $this->reflection = new \ReflectionFunction($this->callable);
            }
        }

        return $this->reflection;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * {@inheritdoc}
     */
    protected function innerCallable()
    {
        return $this->getCallable();
    }
}
