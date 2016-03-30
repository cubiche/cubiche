<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Quantifier;

use Cubiche\Domain\Specification\SpecificationVisitorInterface;

/**
 * All Quantifier Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class All extends Quantifier
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::evaluate()
     */
    public function evaluate($value)
    {
        /** @var bool $result */
        foreach ($this->evaluationIterator($value) as $result) {
            if ($result === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::accept()
     */
    public function accept(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitAll($this);
    }
}
