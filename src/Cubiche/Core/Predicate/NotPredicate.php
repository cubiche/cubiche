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

/**
 * Not Predicate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotPredicate extends Predicate
{
    /**
     * @var PredicateInterface
     */
    protected $predicate;

    /**
     * @param callable $predicate
     */
    public function __construct(callable $predicate)
    {
        $this->predicate = self::from($predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return !$this->predicate()->evaluate($value);
    }

    /**
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public function predicate()
    {
        return $this->predicate;
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return $this->predicate();
    }
}
