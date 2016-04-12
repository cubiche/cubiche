<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Geolocation\Specification;

use Cubiche\Domain\Geolocation\Specification\Constraint\Near;
use Cubiche\Core\Specification\SpecificationVisitorInterface;

/**
 * Geo Specification Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface GeoSpecificationVisitorInterface extends SpecificationVisitorInterface
{
    /**
     * @param Near $near
     *
     * @return mixed
     */
    public function visitNear(Near $near);
}
