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

use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Specification\SpecificationVisitorInterface;

/**
 * FakeSpecification class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FakeSpecification implements SpecificationInterface
{
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
        // TODO: Implement andX() method.
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Specification\SpecificationInterface
     */
    public function orX(SpecificationInterface $specification)
    {
        // TODO: Implement orX() method.
    }

    /**
     * @return \Cubiche\Domain\Specification\SpecificationInterface
     */
    public function not()
    {
        // TODO: Implement not() method.
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
}
