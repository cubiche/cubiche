<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Collections\Tests\Units\Fixtures;

use Cubiche\Domain\Equatable\EquatableInterface;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Specification\SpecificationVisitorInterface;

/**
 * FakeSpecification class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FakeSpecification implements SpecificationInterface, EquatableInterface
{
    /**
     * @var bool
     */
    protected $value;

    /**
     * FakeSpecification constructor.
     *
     * @param bool $value
     */
    public function __construct($value = false)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function evaluate($value)
    {
        // TODO: Implement evaluate() method.
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Specification\SpecificationInterface
     */
    public function andX(SpecificationInterface $specification)
    {
        return new self($this->value() && $specification->value());
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Specification\SpecificationInterface
     */
    public function orX(SpecificationInterface $specification)
    {
        return new self($this->value() || $specification->value());
    }

    /**
     * @return \Cubiche\Domain\Specification\SpecificationInterface
     */
    public function not()
    {
        return new self(!$this->value());
    }

    /**
     * @param SpecificationVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(SpecificationVisitorInterface $visitor)
    {
        // TODO: Implement accept() method.
    }

    /**
     * @param mixed $other
     *
     * @return bool
     */
    public function equals($other)
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->value() === $other->value();
    }
}
