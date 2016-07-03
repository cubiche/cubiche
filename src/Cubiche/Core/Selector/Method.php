<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

use Cubiche\Core\Delegate\Delegate;

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
     * @return $this
     */
    public function with()
    {
        $this->args = \func_get_args();

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
     */
    public function apply($value)
    {
        if (!is_object($value)) {
            throw new \RuntimeException('Trying to call method of non-object');
        }

        if (!method_exists($value, $this->name)) {
            throw new \RuntimeException(\sprintf('Undefined method %s::%s', \get_class($value), $this->name));
        }

        $method = Delegate::fromMethod($value, $this->name);
        if ($method->reflection()->isPrivate() || $method->reflection()->isProtected()) {
            throw new \RuntimeException(
                \sprintf('Trying to call non-public method %s::%s', \get_class($value), $this->name)
            );
        }

        return $method->invokeWith($this->args());
    }
}
