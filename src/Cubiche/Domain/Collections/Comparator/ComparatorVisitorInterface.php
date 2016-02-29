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

use Cubiche\Domain\Comparable\ComparatorVisitorInterface as BaseComparatorVisitorInterface;

/**
 * Comparator Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ComparatorVisitorInterface extends BaseComparatorVisitorInterface
{
    /**
     * @param SelectorComparator $comparator
     *
     * @return mixed
     */
    public function visitSelectorComparator(SelectorComparator $comparator);
}
