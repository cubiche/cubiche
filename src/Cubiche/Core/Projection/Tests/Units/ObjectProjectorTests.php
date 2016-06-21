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

/**
 * Object Projector Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ObjectProjectorTests extends ProjectorInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function projectDataProvider()
    {
        /** @var \Cubiche\Core\Projection\ObjectProjector $projector */
        $projector = $this->newTestedInstance();

        $result = new DefaultProjectionBuilder();
        $result->set('foo', 1);
        $result->set('bar', 2);

        return array(
            array(
                $projector,
                (object) array('foo' => 1, 'bar' => 2),
                new \ArrayIterator(array($result)),
            ),
        );
    }
}
