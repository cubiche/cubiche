<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification\Selector;

use Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface;

/**
 * Property Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Property extends Selector
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\Specification::visit()
     */
    public function visit(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitProperty($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\Selector::apply()
     */
    public function apply($value)
    {
        if (!is_object($value)) {
            throw new \RuntimeException('Trying to get property of non-object');
        }

        if (!property_exists($value, $this->name)) {
            throw new \RuntimeException(\sprintf('Undefined property %s::%s', \get_class($value), $this->name));
        }

        return $value->{$this->name};
    }
}