<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections;

/**
 * Finder Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface FinderInterface extends \Countable, \IteratorAggregate
{
    /**
     * @param int $offset
     * @param int $length
     *
     * @return FinderInterface
     */
    public function sliceFinder($offset, $length = null);
}
