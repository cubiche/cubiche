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

use Cubiche\Core\Visitor\VisitorInterface;

/**
 * Comparator Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ComparatorVisitorInterface extends VisitorInterface
{
    /**
     * @param Comparator $comparator
     *
     * @return mixed
     */
    public function visitComparator(Comparator $comparator);

    /**
     * @param ReverseComparator $comparator
     *
     * @return mixed
     */
    public function visitReverseComparator(ReverseComparator $comparator);

    /**
     * @param Custom $comparator
     *
     * @return mixed
     */
    public function visitCustomComparator(Custom $comparator);

    /**
     * @param SelectorComparator $comparator
     *
     * @return mixed
     */
    public function visitSelectorComparator(SelectorComparator $comparator);

    /**
     * @param MultiComparator $comparator
     *
     * @return mixed
     */
    public function visitMultiComparator(MultiComparator $comparator);
}
