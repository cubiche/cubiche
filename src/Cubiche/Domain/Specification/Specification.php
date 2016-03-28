<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification;

use Cubiche\Domain\Specification\Evaluator\EvaluatorBuilder;

/**
 * Abstract Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Specification implements SpecificationInterface
{
    use SpecificationTrait;

    /**
     * @var EvaluatorBuilder
     */
    protected $evaluatorBuilder = null;

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::evaluate($value)
     */
    public function evaluate($value)
    {
        return $this->evaluatorBuilder()->evaluator($this)->evaluate($value);
    }

    /**
     * @return \Cubiche\Domain\Specification\Evaluator\EvaluatorBuilder
     */
    protected function evaluatorBuilder()
    {
        if ($this->evaluatorBuilder === null) {
            $this->evaluatorBuilder = new EvaluatorBuilder();
        }

        return $this->evaluatorBuilder;
    }
}
