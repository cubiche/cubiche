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
 * Mult Operator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Mult extends Operator
{
    /**
     * @param Expression $firstOperand
     * @param Expression $secondOperand
     */
    public function __construct(Expression $firstOperand, Expression $secondOperand)
    {
        parent::__construct('*', $firstOperand, $secondOperand);
    }
}
