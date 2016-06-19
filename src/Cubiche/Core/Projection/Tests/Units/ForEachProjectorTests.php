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
 * For Each Projector Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ForEachProjectorTests extends ProjectorInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function projectDataProvider()
    {
        $propertyProyector = new PropertyProjector();
        $propertyProyector->add(new Property(Selectors::key('bar'), 'fooBar'));

        /** @var \Cubiche\Core\Projection\ForEachProjector $projector */
        $projector = $this->newTestedInstance(Selectors::key('foo'), $propertyProyector);

        $result1 = new DefaultProjectionBuilder();
        $result1->set('fooBar', 1);

        $result2 = new DefaultProjectionBuilder();
        $result2->set('fooBar', 2);

        return array(
            array(
                $projector,
                array('foo' => array(array('bar' => 1), array('bar' => 2))),
                new \ArrayIterator(array($result1, $result2)),
            ),
        );
    }
}
