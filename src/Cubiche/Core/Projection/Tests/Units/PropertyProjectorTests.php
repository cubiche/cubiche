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
use Cubiche\Core\Selector\Selectors;

/**
 * Property Projector Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PropertyProjectorTests extends ProjectorInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function projectDataProvider()
    {
        /** @var \Cubiche\Core\Projection\PropertyProjector $projector */
        $projector = $this->newDefaultTestedInstance();
        $projector->add(new Property(Selectors::key('foo'), 'foo'));
        $projector->add(new Property(Selectors::key('bar'), 'baz'));

        $result = new DefaultProjectionBuilder();
        $result->set('foo', 1);
        $result->set('baz', 2);

        return array(
            array($projector, array('foo' => 1, 'bar' => 2), new \ArrayIterator(array($result))),
        );
    }
}
