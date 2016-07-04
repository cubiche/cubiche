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

use Cubiche\Core\Visitor\Visitor;

/**
 * Calculator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Calculator extends Visitor
{
    /**
     * @param Sum $sum
     *
     * @return int|float
     */
    public function visitSum(Sum $sum)
    {
        return $sum->firstOperand()->accept($this) + $sum->secondOperand()->accept($this);
    }

    /**
     * @param Mult $mult
     *
     * @return int|float
     */
    public function visitMult(Mult $mult)
    {
        return $mult->firstOperand()->accept($this) * $mult->secondOperand()->accept($this);
    }

    /**
     * @param Value $value
     *
     * @return int|float
     */
    public function visitValue(Value $value)
    {
        return $value->value();
    }
}
