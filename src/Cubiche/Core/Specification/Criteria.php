<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification;

use Cubiche\Core\Selector\Count;
use Cubiche\Core\Selector\Key;
use Cubiche\Core\Selector\Method;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Selector\This;
use Cubiche\Core\Selector\Value;
use Cubiche\Core\Selector\Callback;

/**
 * Criteria Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Criteria
{
    /**
     * @var Selector
     */
    protected static $false = null;

    /**
     * @var Selector
     */
    protected static $true = null;

    /**
     * @var Selector
     */
    protected static $null = null;

    /**
     * @var Selector
     */
    protected static $self = null;

    /**
     * @return \Cubiche\Core\Specification\Selector
     */
    public static function false()
    {
        if (self::$false === null) {
            self::$false = new Selector(new Value(false));
        }

        return self::$false;
    }

    /**
     * @return \Cubiche\Core\Specification\Selector
     */
    public static function true()
    {
        if (self::$true === null) {
            self::$true = new Selector(new Value(true));
        }

        return self::$true;
    }

    /**
     * @return \Cubiche\Core\Specification\Selector
     */
    public static function null()
    {
        if (self::$null === null) {
            self::$null = new Selector(new Value(null));
        }

        return self::$null;
    }

    /**
     * @param string $key
     *
     * @return \Cubiche\Core\Specification\Selector
     */
    public static function key($key)
    {
        return new Selector(new Key($key));
    }

    /**
     * @param string $property
     *
     * @return \Cubiche\Core\Specification\Selector
     */
    public static function property($property)
    {
        return new Selector(new Property($property));
    }

    /**
     * @param string $method
     *
     * @return \Cubiche\Core\Specification\Selector
     */
    public static function method($method)
    {
        return new Selector(new Method($method));
    }

    /**
     * @return \Cubiche\Core\Specification\Selector
     */
    public static function this()
    {
        if (self::$self === null) {
            self::$self = new Selector(new This());
        }

        return self::$self;
    }

    /**
     * @param callable $callable
     *
     * @return \Cubiche\Core\Specification\Selector
     */
    public static function callback(callable $callable)
    {
        return new Selector(new Callback($callable));
    }

    /**
     * @return \Cubiche\Core\Specification\Selector
     */
    public static function count()
    {
        return new Selector(new Count());
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\GreaterThan
     */
    public static function gt($value)
    {
        return self::this()->gt($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\GreaterThanEqual
     */
    public static function gte($value)
    {
        return self::this()->gte($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\LessThan
     */
    public static function lt($value)
    {
        return self::this()->lt($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\LessThanEqual
     */
    public static function lte($value)
    {
        return self::this()->lte($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\Equal
     */
    public static function eq($value)
    {
        return self::this()->eq($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\NotEqual
     */
    public static function neq($value)
    {
        return self::this()->neq($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\Same
     */
    public static function same($value)
    {
        return self::this()->same($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\NotSame
     */
    public static function notSame($value)
    {
        return self::this()->notSame($value);
    }

    /**
     * @return \Cubiche\Core\Specification\Constraint\Same
     */
    public static function isNull()
    {
        return self::this()->isNull();
    }

    /**
     * @return \Cubiche\Core\Specification\Constraint\NotSame
     */
    public static function notNull()
    {
        return self::this()->notNull();
    }

    /**
     * @return \Cubiche\Core\Specification\Constraint\Same
     */
    public static function isTrue()
    {
        return self::this()->isTrue();
    }

    /**
     * @return \Cubiche\Core\Specification\Constraint\Same
     */
    public static function isFalse()
    {
        return self::this()->isFalse();
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Core\Specification\Quantifier\All
     */
    public static function all(SpecificationInterface $specification)
    {
        return self::this()->all($specification);
    }

    /**
     * @param int                    $count
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Core\Specification\Quantifier\AtLeast
     */
    public static function atLeast($count, SpecificationInterface $specification)
    {
        return self::this()->atLeast($count, $specification);
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Core\Specification\Quantifier\AtLeast
     */
    public static function any(SpecificationInterface $specification)
    {
        return self::this()->any($specification);
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Core\Specification\SpecificationInterface
     */
    public static function not(SpecificationInterface $specification)
    {
        return $specification->not();
    }
}
