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
use Cubiche\Core\Projection\ObjectProjector;
use Cubiche\Core\Projection\Projectors;
use Cubiche\Core\Selector\Selectors;

/**
 * Extended Projector Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ExtendedProjectorTests extends ProjectorInterfaceTestCase
{
    /**
     * @return array
     */
    protected function constructTest()
    {
        /** @var \Cubiche\Core\Projection\ExtendedProjector $projector */
        $projector = $this->newTestedInstance(new ObjectProjector());

        $result = new DefaultProjectionBuilder();
        $result->set('foo', 1);

        return array(
            $projector,
            (object) array('foo' => 1),
            new \ArrayIterator(array($result)),
        );
    }

    /**
     * @return array
     */
    protected function joinTest()
    {
        $projector = Projectors
            ::select(Selectors::key('foo'))
            ->join(Projectors::forAll(
                Selectors::key('list'),
                Projectors::selectAll()
            ))->on('foo')->eq('bar')
        ;

        return array(
            $projector,
            array('foo' => 1, 'list' => array(array('bar' => 1), array('bar' => 2), array('bar' => 3))),
            new \ArrayIterator(array(
                DefaultProjectionBuilder::fromObject((object) array('foo' => 1, 'bar' => 1)),
            )),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function projectDataProvider()
    {
        return array(
            $this->constructTest(),
            $this->joinTest(),
        );
    }
}
