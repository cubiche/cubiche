<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Projection;

use Cubiche\Core\Selector\SelectorInterface;

/**
 * Projection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Projections
{
    /**
     * @param SelectorInterface $selector
     *
     * @return \Cubiche\Core\Projection\PropertyProjectionBuilder
     */
    public static function select(SelectorInterface $selector)
    {
        return new PropertyProjectionBuilder($selector);
    }

    /**
     * @param SelectorInterface   $selector
     * @param ProjectionInterface $projection
     *
     * @return \Cubiche\Core\Projection\ForEachProjection
     */
    public static function forAll(SelectorInterface $selector, ProjectionInterface $projection)
    {
        return new ForEachProjection($selector, $projection);
    }
}
