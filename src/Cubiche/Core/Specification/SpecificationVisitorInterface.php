<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification;

use Cubiche\Core\Specification\Constraint\Equal;
use Cubiche\Core\Specification\Constraint\GreaterThan;
use Cubiche\Core\Specification\Constraint\GreaterThanEqual;
use Cubiche\Core\Specification\Constraint\LessThan;
use Cubiche\Core\Specification\Constraint\LessThanEqual;
use Cubiche\Core\Specification\Constraint\NotEqual;
use Cubiche\Core\Specification\Constraint\NotSame;
use Cubiche\Core\Specification\Constraint\Same;
use Cubiche\Core\Specification\Quantifier\All;
use Cubiche\Core\Specification\Quantifier\AtLeast;
use Cubiche\Core\Visitor\VisitorInterface;

/**
 * Specification Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SpecificationVisitorInterface extends VisitorInterface
{
    /**
     * @param AndSpecification $and
     *
     * @return mixed
     */
    public function visitAnd(AndSpecification $and);

    /**
     * @param OrSpecification $or
     *
     * @return mixed
     */
    public function visitOr(OrSpecification $or);

    /**
     * @param NotSpecification $not
     *
     * @return mixed
     */
    public function visitNot(NotSpecification $not);

    /**
     * @param Selector $selector
     *
     * @return mixed
     */
    public function visitSelector(Selector $selector);

    /**
     * @param GreaterThan $gt
     *
     * @return mixed
     */
    public function visitGreaterThan(GreaterThan $gt);

    /**
     * @param GreaterThanEqual $gte
     *
     * @return mixed
     */
    public function visitGreaterThanEqual(GreaterThanEqual $gte);

    /**
     * @param LessThan $lt
     *
     * @return mixed
     */
    public function visitLessThan(LessThan $lt);

    /**
     * @param LessThanEqual $lte
     *
     * @return mixed
     */
    public function visitLessThanEqual(LessThanEqual $lte);

    /**
     * @param Equal $eq
     *
     * @return mixed
     */
    public function visitEqual(Equal $eq);

    /**
     * @param NotEqual $neq
     *
     * @return mixed
     */
    public function visitNotEqual(NotEqual $neq);

    /**
     * @param Same $same
     *
     * @return mixed
     */
    public function visitSame(Same $same);

    /**
     * @param NotSame $specification
     *
     * @return mixed
     */
    public function visitNotSame(NotSame $specification);

    /**
     * @param All $all
     *
     * @return mixed
     */
    public function visitAll(All $all);

    /**
     * @param AtLeast $atLeast
     *
     * @return mixed
     */
    public function visitAtLeast(AtLeast $atLeast);
}
