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

use Cubiche\Domain\Specification\Constraint\Equal;
use Cubiche\Domain\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Specification\Constraint\LessThan;
use Cubiche\Domain\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Specification\Constraint\NotEqual;
use Cubiche\Domain\Specification\Constraint\NotSame;
use Cubiche\Domain\Specification\Constraint\Same;
use Cubiche\Domain\Specification\Quantifier\All;
use Cubiche\Domain\Specification\Quantifier\AtLeast;

/**
 * Specification Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SpecificationVisitorInterface extends SelectorVisitorInterface
{
    /**
     * @param AndSpecification $specification
     *
     * @return mixed
     */
    public function visitAnd(AndSpecification $specification);

    /**
     * @param OrSpecification $specification
     *
     * @return mixed
     */
    public function visitOr(OrSpecification $specification);

    /**
     * @param NotSpecification $specification
     *
     * @return mixed
     */
    public function visitNot(NotSpecification $specification);

    /**
     * @param GreaterThan $specification
     *
     * @return mixed
     */
    public function visitGreaterThan(GreaterThan $specification);

    /**
     * @param GreaterThanEqual $specification
     *
     * @return mixed
     */
    public function visitGreaterThanEqual(GreaterThanEqual $specification);

    /**
     * @param LessThan $specification
     *
     * @return mixed
     */
    public function visitLessThan(LessThan $specification);

    /**
     * @param LessThanEqual $specification
     *
     * @return mixed
     */
    public function visitLessThanEqual(LessThanEqual $specification);

    /**
     * @param Equal $specification
     *
     * @return mixed
     */
    public function visitEqual(Equal $specification);

    /**
     * @param NotEqual $specification
     *
     * @return mixed
     */
    public function visitNotEqual(NotEqual $specification);

    /**
     * @param Same $specification
     *
     * @return mixed
     */
    public function visitSame(Same $specification);

    /**
     * @param NotSame $specification
     *
     * @return mixed
     */
    public function visitNotSame(NotSame $specification);

    /**
     * @param All $specification
     *
     * @return mixed
     */
    public function visitAll(All $specification);

    /**
     * @param AtLeast $specification
     *
     * @return mixed
     */
    public function visitAtLeast(AtLeast $specification);
}
