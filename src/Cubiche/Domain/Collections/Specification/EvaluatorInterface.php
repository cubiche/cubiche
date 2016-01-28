<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification;

/**
 * Evaluator Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface EvaluatorInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function evaluate($value);
}
