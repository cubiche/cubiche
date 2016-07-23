<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Predicate\Quantifier;

use Cubiche\Core\Predicate\Predicate;
use Cubiche\Core\Predicate\PredicateInterface;
use Cubiche\Core\Selector\Selector;
use Cubiche\Core\Selector\SelectorInterface;

/**
 * Abstract Quantifier Predicate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Quantifier extends Predicate
{
    /**
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @var PredicateInterface
     */
    protected $predicate;

    /**
     * @param callable|mixed $selector
     * @param callable       $predicate
     */
    public function __construct($selector, callable $predicate)
    {
        $this->selector = Selector::from($selector);
        $this->predicate = self::from($predicate);
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public function predicate()
    {
        return $this->predicate;
    }
}
