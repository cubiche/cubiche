<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Comparator;

use Cubiche\Domain\Specification\SelectorInterface;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ComparatorVisitorInterface as BaseComparatorVisitorInterface;

/**
 * Selector Comparator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorComparator extends Comparator
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
     * @return \Cubiche\Domain\Specification\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * @return \Cubiche\Domain\Collections\Comparator\Order
     */
    public function order()
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorInterface::compare()
     */
    public function compare($a, $b)
    {
        return parent::compare($this->selector->apply($a), $this->selector->apply($a)) * $this->order()->toNative();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Comparator::accept()
     */
    public function accept(BaseComparatorVisitorInterface $visitor)
    {
        if ($visitor instanceof ComparatorVisitorInterface) {
            return $visitor->visitSelectorComparator($this);
        }

        return $visitor->visitComparator($this);
    }
}
