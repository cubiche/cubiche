<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable;

use Cubiche\Core\Delegate\Delegate;

/**
 * Selector Comparator class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorComparator extends Comparator
{
    /**
     * @var Delegate
     */
    protected $selector;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @param callable $selector
     * @param Order    $order
     */
    public function __construct(callable $selector, Order $order)
    {
        $this->selector = new Delegate($selector);
        $this->order = $order;
    }

    /**
     * @return callable
     */
    public function selector()
    {
        return $this->selector->getCallable();
    }

    /**
     * @return \Cubiche\Core\Comparable\Order
     */
    public function order()
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */
    public function compare($a, $b)
    {
        return $this->order()->getValue() * parent::compare(
            $this->selector->__invoke($a),
            $this->selector->__invoke($b)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function reverse()
    {
        return new self($this->selector(), new Order(-1 * $this->order()->getValue()));
    }
}
