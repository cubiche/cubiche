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
use Cubiche\Core\Projection\ForEachProjector;
use Cubiche\Core\Projection\ObjectProjector;
use Cubiche\Core\Projection\Property;
use Cubiche\Core\Selector\Selectors;
use Cubiche\Core\Specification\Criteria;

/**
 * Where Projector Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class WhereProjectorTests extends ProjectorInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function projectDataProvider()
    {
        $forEachProjector = new ForEachProjector(Selectors::key('list'), new ObjectProjector());

        /** @var \Cubiche\Core\Projection\WhereProjector $projector */
        $projector = $this->newTestedInstance(Criteria::property('foo')->eq(1), $forEachProjector);

        $result = new DefaultProjectionBuilder();
        $result->set('foo', 1);

        return array(
            array(
                $projector,
                array('list' => array(
                    (object) array('foo' => 1),
                    (object) array('foo' => 2),
                    (object) array('foo' => 3),
                )),
                new \ArrayIterator(array($result)),
            ),
        );
    }
}
