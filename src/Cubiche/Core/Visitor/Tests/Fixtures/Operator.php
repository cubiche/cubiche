<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Visitor\Tests\Fixtures;

/**
 * Operator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Operator extends Expression
{
    /**
     * @var string
     */
    protected $operator;

    /**
     * @var Expression
     */
    protected $firstOperand;

    /**
     * @var Expression
     */
    protected $secondOperand;

    /**
     * @param string     $operator
     * @param Expression $firstOperand
     * @param Expression $secondOperand
     */
    public function __construct($operator, Expression $firstOperand, Expression $secondOperand)
    {
        $this->operator = $operator;
        $this->firstOperand = $firstOperand;
        $this->secondOperand = $secondOperand;
    }

    /**
     * @return string
     */
    public function operator()
    {
        return $this->operator;
    }

    /**
     * @return \Cubiche\Core\Visitor\Tests\Fixtures\Expression
     */
    public function firstOperand()
    {
        return $this->firstOperand;
    }

    /**
     * @return \Cubiche\Core\Visitor\Tests\Fixtures\Expression
     */
    public function secondOperand()
    {
        return $this->secondOperand;
    }
}
