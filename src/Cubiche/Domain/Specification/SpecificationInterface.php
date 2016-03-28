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

/**
 * Specification Interface.
 *
 * @method SpecificationInterface and(SpecificationInterface $specification)
 * @method SpecificationInterface or(SpecificationInterface $specification)
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SpecificationInterface extends EvaluatorInterface
{
    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Specification\SpecificationInterface
     */
    public function andX(SpecificationInterface $specification);

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Specification\SpecificationInterface
     */
    public function orX(SpecificationInterface $specification);

    /**
     * @return \Cubiche\Domain\Specification\SpecificationInterface
     */
    public function not();

    /**
     * @param SpecificationVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(SpecificationVisitorInterface $visitor);
}
