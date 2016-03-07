<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Selector;

use Cubiche\Domain\Specification\SpecificationVisitorInterface;
use Cubiche\Domain\Delegate\Delegate;

/**
 * Method Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Method extends Field
{
    /**
     * @var array
     */
    protected $args;

    /**
     * @param string $name
     * @param array  $args
     */
    public function __construct($name, array $args = array())
    {
        parent::__construct($name);

        $this->args = $args;
    }

    /**
     * @return \Cubiche\Domain\Specification\MethodSelector
     */
    public function with()
    {
        $this->args = func_get_args();

        return $this;
    }

    /**
     * @return array
     */
    public function args()
    {
        return $this->args;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::accept()
     */
    public function accept(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitMethod($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\Selector::apply()
     */
    public function apply($value)
    {
        if (!is_object($value)) {
            throw new \RuntimeException('Trying to call method of non-object');
        }

        if (!method_exists($value, $this->name)) {
            throw new \RuntimeException(\sprintf('Undefined method %s::%s', \get_class($value), $this->name));
        }

        $reflection = new \ReflectionMethod(\get_class($value), $this->name);
        if ($reflection->isPrivate() || $reflection->isProtected()) {
            throw new \RuntimeException(
                \sprintf('Trying to call non-public method %s::%s', \get_class($value), $this->name)
            );
        }

        return Delegate::fromMethod($value, $this->name)->invokeWith($this->args);
    }
}
