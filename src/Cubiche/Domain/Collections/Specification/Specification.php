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

use Cubiche\Domain\Collections\Specification\Evaluator\EvaluatorVisitor;

/**
 * Abstract Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Specification implements SpecificationInterface
{
    /**
     * @var EvaluatorVisitor
     */
    protected $evaluatorVisitor = null;

    /**
     * @param SpecificationVisitorInterface $visitor
     *
     * @return mixed
     */
    abstract public function visit(SpecificationVisitorInterface $visitor);

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationInterface::and($specification)
     */
    public function andX(SpecificationInterface $specification)
    {
        return new AndSpecification($this, $specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationInterface::or($specification)
     */
    public function orX(SpecificationInterface $specification)
    {
        return new OrSpecification($this, $specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationInterface::not()
     */
    public function not()
    {
        return new NotSpecification($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationInterface::evaluate($value)
     */
    public function evaluate($value)
    {
        return $this->evaluatorVisitor()->evaluator($this)->evaluate($value);
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\Evaluator\EvaluatorVisitor
     */
    protected function evaluatorVisitor()
    {
        if ($this->evaluatorVisitor === null) {
            $this->evaluatorVisitor = new EvaluatorVisitor();
        }

        return $this->evaluatorVisitor;
    }
}
