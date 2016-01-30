<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification;

use Cubiche\Domain\Collections\Specification\Selector\Key;
use Cubiche\Domain\Collections\Specification\Selector\Method;
use Cubiche\Domain\Collections\Specification\Selector\Property;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Specification\Selector\Custom;

/**
 * Criteria Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Criteria
{
    /**
     * @var Value
     */
    protected static $false = null;

    /**
     * @var Value
     */
    protected static $true = null;

    /**
     * @var Value
     */
    protected static $null = null;

    /**
     * @var This
     */
    protected static $self = null;

    /**
     * @return \Cubiche\Domain\Collections\Specification\Value
     */
    public static function false()
    {
        if (self::$false === null) {
            self::$false = new Value(false);
        }

        return self::$false;
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\Value
     */
    public static function true()
    {
        if (self::$true === null) {
            self::$true = new Value(true);
        }

        return self::$true;
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\Value
     */
    public static function null()
    {
        if (self::$null === null) {
            self::$null = new Value(null);
        }

        return self::$null;
    }

    /**
     * @param string $key
     *
     * @return \Cubiche\Domain\Collections\Specification\Selector\Key
     */
    public static function key($key)
    {
        return new Key($key);
    }

    /**
     * @param unknown $property
     *
     * @return \Cubiche\Domain\Collections\Specification\Selector\Property
     */
    public static function property($property)
    {
        return new Property($property);
    }

    /**
     * @param string $method
     *
     * @return \Cubiche\Domain\Collections\Specification\Selector\Method
     */
    public static function method($method)
    {
        return new Method($method);
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\Selector\This
     */
    public static function this()
    {
        if (self::$self === null) {
            self::$self = new This();
        }

        return self::$self;
    }

    /**
     * @param callable $callable
     *
     * @return \Cubiche\Domain\Collections\Specification\Selector\Custom
     */
    public static function custom($callable)
    {
        return new Custom($callable);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\GreaterThan
     */
    public static function gt($value)
    {
        return self::this()->gt($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\GreaterThanEqual
     */
    public function gte($value)
    {
        return self::this()->gte($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\LessThan
     */
    public function lt($value)
    {
        return self::this()->lt($value);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\LessThanEqual
     */
    public function lte($value)
    {
        return self::this()->lte($value);
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Collections\Specification\Quantifier\All
     */
    public static function all(SpecificationInterface $specification)
    {
        return self::this()->all($specification);
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Collections\Specification\SpecificationInterface
     */
    public static function not(SpecificationInterface $specification)
    {
        return $specification->not();
    }
}
