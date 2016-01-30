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

use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Collections\Specification\Quantifier\All;
use Cubiche\Domain\Collections\Specification\Selector\Key;
use Cubiche\Domain\Collections\Specification\Selector\Method;
use Cubiche\Domain\Collections\Specification\Selector\Property;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;

/**
 * Specification Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SpecificationVisitorInterface
{
    /**
     * @param Specification $specification
     *
     * @return mixed
     */
    public function visit(Specification $specification);

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
     * @param Value $specification
     *
     * @return mixed
     */
    public function visitValue(Value $specification);

    /**
     * @param Key $specification
     *
     * @return mixed
     */
    public function visitKey(Key $specification);

    /**
     * @param Property $specification
     *
     * @return mixed
     */
    public function visitProperty(Property $specification);

    /**
     * @param Method $specification
     *
     * @return mixed
     */
    public function visitMethod(Method $specification);

    /**
     * @param This $specification
     *
     * @return mixed
     */
    public function visitThis(This $specification);

    /**
     * @param All $specification
     *
     * @return mixed
     */
    public function visitAll(All $specification);

    /**
     * @param GreaterThan $specification
     *
     * @return mixed
     */
    public function visitGreaterThan(GreaterThan $specification);
}
