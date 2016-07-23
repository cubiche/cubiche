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
 * Abstract Binary Predicate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class BinaryPredicate extends Predicate
{
    /**
     * @var PredicateInterface
     */
    protected $firstOperand;

    /**
     * @var PredicateInterface
     */
    protected $secondOperand;

    /**
     * @param callable $firstOperand
     * @param callable $secondOperand
     */
    public function __construct(callable $firstOperand, callable $secondOperand)
    {
        $this->firstOperand = self::from($firstOperand);
        $this->secondOperand = self::from($secondOperand);
    }

    /**
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public function firstOperand()
    {
        return $this->firstOperand;
    }

    /**
     * @return \Cubiche\Core\Predicate\PredicateInterface
     */
    public function secondOperand()
    {
        return $this->secondOperand;
    }
}
