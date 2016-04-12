<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Geolocation\Doctrine\ODM\MongoDB\Query;

use Cubiche\Domain\Geolocation\Specification\GeoSpecificationVisitorInterface;
use Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query\SpecificationVisitor;

/**
 * Geo Specification Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class GeoSpecificationVisitor extends SpecificationVisitor implements GeoSpecificationVisitorInterface
{
    use GeoSpecificationVisitorTrait;
}
