<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Specification\Quantifier;

use Cubiche\Core\Specification\SpecificationVisitorInterface;

/**
 * All Quantifier Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class All extends Quantifier
{
    /**
     * {@inheritdoc}
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
     */
    public function acceptSpecificationVisitor(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitAll($this);
    }
}
