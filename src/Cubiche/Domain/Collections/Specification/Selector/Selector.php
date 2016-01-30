<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification\Selector;

use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Collections\Specification\Quantifier\All;
use Cubiche\Domain\Collections\Specification\SelectorInterface;
use Cubiche\Domain\Collections\Specification\Specification;
use Cubiche\Domain\Collections\Specification\SpecificationInterface;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThanEqual;

/**
 * Abstract Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Selector extends Specification implements SelectorInterface
{
    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Collections\Specification\Quantifier\All
     */
    public function all(SpecificationInterface $specification)
    {
        return new All($this, $specification);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\GreaterThan
     */
    public function gt($value)
    {
        return new GreaterThan($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\GreaterThanEqual
     */
    public function gte($value)
    {
        return new GreaterThanEqual($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\SelectorInterface
     */
    protected function selector($value)
    {
        return $value instanceof SelectorInterface ? $value : new Value($value);
    }
}
