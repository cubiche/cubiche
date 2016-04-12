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

use Cubiche\Core\Selector\Property;
use Cubiche\Domain\Geolocation\Specification\Constraint\Near;

/**
 * Geo Specification Visitor Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait GeoSpecificationVisitorTrait
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Geolocation\Specification\GeoSpecificationVisitorInterface::visitNear()
     */
    public function visitNear(Near $near)
    {
        $field = $this->createField($near->selector()->select(new Property('coordinate')));
        $this->queryBuilder->field($field->name())->near(array(
            'type' => 'Point',
            'coordinates' => array(
                $near->coordinate()->longitude()->toNative(),
                $near->coordinate()->latitude()->toNative(),
            ),
        ));
    }
}
