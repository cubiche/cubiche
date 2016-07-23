<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Predicate;

use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\Selector\Selectors;

/**
 * Criteria class.
 *
 * @method static \Cubiche\Core\Predicate\SelectorPredicate key(string $name)
 * @method static \Cubiche\Core\Predicate\SelectorPredicate property(string $name)
 * @method static \Cubiche\Core\Predicate\SelectorPredicate method(string $name)
 * @method static \Cubiche\Core\Predicate\SelectorPredicate callback(callable $callback)
 * @method static \Cubiche\Core\Predicate\SelectorPredicate count()
 * @method static \Cubiche\Core\Predicate\Constraint\GreaterThan gt($value, callable $comparator = null)
 * @method static \Cubiche\Core\Predicate\Constraint\GreaterThanEqual gte($value, callable $comparator = null)
 * @method static \Cubiche\Core\Predicate\Constraint\LessThan lt($value, callable $comparator = null)
 * @method static \Cubiche\Core\Predicate\Constraint\LessThanEqual lte($value, callable $comparator = null)
 * @method static \Cubiche\Core\Predicate\Constraint\Equal eq($value, callable $equalityComparer = null)
 * @method static \Cubiche\Core\Predicate\Constraint\NotEqual neq($value, callable $equalityComparer = null)
 * @method static \Cubiche\Core\Predicate\Constraint\Equal isNull()
 * @method static \Cubiche\Core\Predicate\Constraint\NotEqual isNotNull()
 * @method static \Cubiche\Core\Predicate\Constraint\Equal isTrue()
 * @method static \Cubiche\Core\Predicate\Constraint\Equal isFalse()
 * @method static \Cubiche\Core\Predicate\Quantifier\All all(callable $predicate)
 * @method static \Cubiche\Core\Predicate\Quantifier\AtLeast atLeast(int $count, callable $predicate)
 * @method static \Cubiche\Core\Predicate\Quantifier\Any any(callable $predicate)
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Criteria
{
    /**
     * @var PredicateInterface
     */
    protected static $false = null;

    /**
     * @var PredicateInterface
     */
    protected static $true = null;

    /**
     * @var SelectorPredicate
     */
    protected static $self = null;

    /**
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public static function false()
    {
        if (self::$false === null) {
            self::$false = Predicate::from(Selectors::false());
        }

        return self::$false;
    }

    /**
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public static function true()
    {
        if (self::$true === null) {
            self::$true = Predicate::from(Selectors::true());
        }

        return self::$true;
    }

    /**
     * @param callable $predicate
     *
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public static function not(callable $predicate)
    {
        return Predicate::from($predicate)->not();
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return Delegate::fromMethod(self::this(), $method)->invokeWith($arguments);
    }

    /**
     * @return \Cubiche\Core\Predicate\SelectorPredicate
     */
    protected static function this()
    {
        if (self::$self === null) {
            self::$self = new SelectorPredicate(Selectors::this()->selector());
        }

        return self::$self;
    }
}
