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

use Cubiche\Core\Delegate\AbstractCallable;
use Cubiche\Core\Visitor\VisiteeTrait;

/**
 * Abstract Predicate class.
 *
 * @method Predicate and(callable $predicate)
 * @method Predicate or(callable $predicate)
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Predicate extends AbstractCallable implements PredicateInterface
{
    use VisiteeTrait;

    /**
     * @param callable $predicate
     *
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public static function from(callable $predicate)
    {
        if ($predicate instanceof PredicateInterface) {
            return $predicate;
        }

        return new SelectorPredicate($predicate);
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, array $arguments)
    {
        if ($method === 'and' || $method === 'or') {
            return \call_user_func_array(array($this, $method.'Predicate'), $arguments);
        }

        throw new \BadMethodCallException(\sprintf('Call to undefined method %s::%s', \get_class($this), $method));
    }

    /**
     * {@inheritdoc}
     */
    public function andPredicate(callable $predicate)
    {
        return new AndPredicate($this, $predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function orPredicate(callable $predicate)
    {
        return new OrPredicate($this, $predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return new NotPredicate($this);
    }

    /**
     * {@inheritdoc}
     */
    protected function innerCallback()
    {
        return array($this, 'evaluate');
    }
}
