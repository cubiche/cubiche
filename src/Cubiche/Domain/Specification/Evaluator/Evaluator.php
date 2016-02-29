<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Evaluator;

use Cubiche\Domain\Specification\EvaluatorInterface;
use Cubiche\Domain\Delegate\Delegate;

/**
 * Evaluator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Evaluator extends Delegate implements EvaluatorInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\EvaluatorInterface::evaluate()
     */
    public function evaluate($value)
    {
        return $this->__invoke($value);
    }
}
