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
    protected $target;

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
     * @param callable $target
     */
    public function __construct($target)
    {
        if (!\is_callable($target)) {
            throw new \InvalidArgumentException('The $target argument must be a callable.');
        }

        if (\is_string($target) && \strpos($target, '::')) {
            $target = explode('::', $target);
        }

        $this->target = $target;
    }

    /**
     * @return \ReflectionFunction|\ReflectionMethod
     */
    public function reflection()
    {
        if ($this->reflection === null) {
            if (\is_array($this->target)) {
                $this->reflection = new \ReflectionMethod($this->target[0], $this->target[1]);
            } elseif (\is_object($this->target) && !$this->target instanceof \Closure) {
                $this->reflection = new \ReflectionMethod($this->target, '__invoke');
            } else {
                $this->reflection = new \ReflectionFunction($this->target);
            }
        }

        return $this->reflection;
    }

    /**
     * @return callable
     */
    public function target()
    {
        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    protected function innerCallback()
    {
        return $this->target();
    }
}
