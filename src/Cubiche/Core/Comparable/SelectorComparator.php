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

use Cubiche\Core\Selector\SelectorInterface;

/**
 * Selector Comparator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorComparator extends AbstractComparator
{
    /**
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @param SelectorInterface $selector
     * @param Order             $order
     */
    public function __construct(SelectorInterface $selector, Order $order)
    {
        $this->selector = $selector;
        $this->order = $order;
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
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
        return parent::compare($this->selector->apply($a), $this->selector->apply($b)) * $this->order()->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function reverse()
    {
        return new self($this->selector(), new Order(-1 * $this->order()->getValue()));
    }

    /**
     * {@inheritdoc}
     */
    public function acceptComparatorVisitor(ComparatorVisitorInterface $visitor)
    {
        return $visitor->visitSelectorComparator($this);
    }
}
