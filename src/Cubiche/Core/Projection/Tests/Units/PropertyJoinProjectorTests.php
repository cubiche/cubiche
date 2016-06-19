<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Projection\Tests\Units;

use Cubiche\Core\Projection\DefaultProjectionBuilder;
use Cubiche\Core\Projection\Property;
use Cubiche\Core\Projection\PropertyProjector;
use Cubiche\Core\Selector\Selectors;

/**
 * Property Join Projector Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PropertyJoinProjectorTests extends ProjectorInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function projectDataProvider()
    {
        $propertyProyector1 = new PropertyProjector();
        $propertyProyector1->add(new Property(Selectors::key('foo'), 'foo'));

        $propertyProyector2 = new PropertyProjector();
        $propertyProyector2->add(new Property(Selectors::key('bar'), 'bar'));
        $propertyProyector2->add(new Property(Selectors::key('baz'), 'baz'));

        /** @var \Cubiche\Core\Projection\PropertyJoinProjector $projector */
        $projector = $this->newTestedInstance($propertyProyector1, $propertyProyector2, 'foo', 'baz');
        $projector->exclusiveModeOn();

        $result = new DefaultProjectionBuilder();
        $result->set('foo', 1);
        $result->set('bar', 2);

        return array(
            array(
                $projector,
                array('foo' => 1, 'bar' => 2, 'baz' => 1),
                new \ArrayIterator(array($result)),
            ),
        );
    }
}
