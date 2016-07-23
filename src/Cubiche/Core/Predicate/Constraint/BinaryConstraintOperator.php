<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Predicate\Constraint;

use Cubiche\Core\Predicate\Predicate;
use Cubiche\Core\Selector\Selector;
use Cubiche\Core\Selector\SelectorInterface;

/**
 * Abstract Binary Constraint Operator class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class BinaryConstraintOperator extends Predicate
{
    /**
     * @var SelectorInterface
     */
    protected $firstSelector;

    /**
     * @var SelectorInterface
     */
    protected $secondSelector;

    /**
     * @param callable|mixed $firstSelector
     * @param callable|mixed $secondSelector
     */
    public function __construct($firstSelector, $secondSelector)
    {
        $this->firstSelector = Selector::from($firstSelector);
        $this->secondSelector = Selector::from($secondSelector);
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function firstSelector()
    {
        return $this->firstSelector;
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function secondSelector()
    {
        return $this->secondSelector;
    }
}
