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
 * Expression to String Converter Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ExpressionToStringConverter extends Visitor
{
    /**
     * @param Operator $op
     *
     * @return string
     */
    public function visitOperator(Operator $op)
    {
        return '('.$op->firstOperand()->accept($this).$op->operator().$op->secondOperand()->accept($this).')';
    }

    /**
     * @param Value $value
     *
     * @return string
     */
    public function visitValue(Value $value)
    {
        return (string) $value->value();
    }

    /**
     * @param Variable $variable
     *
     * @return string
     */
    public function visitVariable(Variable $variable)
    {
        return $variable->name();
    }
}
