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

use Cubiche\Core\Selector\Custom;
use Cubiche\Core\Selector\Key;
use Cubiche\Core\Selector\Method;
use Cubiche\Core\Selector\Property as PropertySelector;
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

    public static function forAll($selector, $projection)
    {
        $forEach = new ForEachProjection($selector, $projection);

        return $forEach;
    }

    /**
     * @param string $key
     *
     * @return \Cubiche\Core\Projection\Property
     */
    public static function key($key)
    {
        return new Key($key);
    }

    /**
     * @param string $property
     *
     * @return \Cubiche\Core\Projection\Property
     */
    public static function property($property)
    {
        return new PropertySelector($property);
    }

    /**
     * @param string $method
     *
     * @return \Cubiche\Core\Projection\Property
     */
    public static function method($method)
    {
        return new Method($method);
    }

    /**
     * @param callable $callable
     *
     * @return \Cubiche\Core\Projection\Property
     */
    public static function custom(callable $callable)
    {
        return new Property(new Custom($callable));
    }
}
