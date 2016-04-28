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

/**
 * Value Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Value extends Selector
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($value)
    {
        return $this->value();
    }

    /**
     * {@inheritdoc}
     */
    public function acceptSelectorVisitor(SelectorVisitorInterface $visitor)
    {
        return $visitor->visitValue($this);
    }
}
