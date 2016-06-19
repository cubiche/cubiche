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
 * Projectors Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Projectors
{
    /**
     * @param SelectorInterface $selector
     *
     * @return \Cubiche\Core\Projection\PropertyProjectorBuilder
     */
    public static function select(SelectorInterface $selector)
    {
        return new PropertyProjectorBuilder($selector);
    }

    /**
     * @return \Cubiche\Core\Projection\ExtendedProjector
     */
    public static function selectAll()
    {
        return new ExtendedProjector(new ObjectProjector());
    }

    /**
     * @param SelectorInterface  $selector
     * @param ProjectorInterface $projector
     *
     * @return \Cubiche\Core\Projection\ExtendedProjector
     */
    public static function forAll(SelectorInterface $selector, ProjectorInterface $projector)
    {
        return new ExtendedProjector(new ForEachProjector($selector, $projector));
    }
}
