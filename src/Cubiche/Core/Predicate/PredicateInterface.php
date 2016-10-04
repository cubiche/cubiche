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

use Cubiche\Core\Visitor\VisiteeInterface;
use Cubiche\Core\Delegate\CallableInterface;

/**
 * Predicate interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface PredicateInterface extends CallableInterface, VisiteeInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function evaluate($value);

    /**
     * @param callable $predicate
     *
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public function andPredicate(callable $predicate);

    /**
     * @param callable $predicate
     *
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public function orPredicate(callable $predicate);

    /**
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public function not();
}
