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

use Cubiche\Core\Projection\Projectors;
use Cubiche\Core\Selector\Selectors;
use Cubiche\Core\Projection\DefaultProjectionBuilder;

/**
 * Projectors Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ProjectorsTests extends ProjectorInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    public function testClass()
    {
        $this->skip('');
    }

    /**
     * @return array
     */
    protected function selectTest()
    {
        $projector = Projectors
            ::select(Selectors::key('foo'))
            ->select(Selectors::key('bar'))
            ->select(Selectors::callback(function ($value) {
                return $value['foo'] * $value['bar'];
            }))->as('fooBar')
        ;

        $result = new DefaultProjectionBuilder();
        $result->set('foo', 2);
        $result->set('bar', 3);
        $result->set('fooBar', 6);

        return array(
            $projector,
            array('foo' => 2, 'bar' => 3, 'baz' => 'baz'),
            new \ArrayIterator(array($result)),
        );
    }

    /**
     * @return array
     */
    protected function forAllTest()
    {
        $projector = Projectors
            ::select(Selectors::key('foo'))
            ->select(Selectors::key('list')->count())->as('count')
            ->join(Projectors::forAll(
                Selectors::key('list'),
                Projectors
                    ::select(Selectors::callback(function ($value) {
                        return $value['bar'] * 3;
                    }))->as('bar')
            ))
        ;

        return array(
            $projector,
            array('foo' => 1, 'list' => array(array('bar' => 1), array('bar' => 2), array('bar' => 3))),
            new \ArrayIterator(array(
                DefaultProjectionBuilder::fromObject((object) array('foo' => 1, 'count' => 3, 'bar' => 3)),
                DefaultProjectionBuilder::fromObject((object) array('foo' => 1, 'count' => 3, 'bar' => 6)),
                DefaultProjectionBuilder::fromObject((object) array('foo' => 1, 'count' => 3, 'bar' => 9)),
            )),
        );
    }

    protected function selectAllTest()
    {
        $projector = Projectors
            ::select(Selectors::key('foo'))
            ->join(Projectors::forAll(
                Selectors::key('list'),
                Projectors::selectAll()
            ))
        ;

        return array(
            $projector,
            array('foo' => 1, 'list' => array(array('bar' => 1), array('bar' => 2), array('bar' => 3))),
            new \ArrayIterator(array(
                DefaultProjectionBuilder::fromObject((object) array('foo' => 1, 'bar' => 1)),
                DefaultProjectionBuilder::fromObject((object) array('foo' => 1, 'bar' => 2)),
                DefaultProjectionBuilder::fromObject((object) array('foo' => 1, 'bar' => 3)),
            )),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function projectDataProvider()
    {
        return array(
            $this->selectTest(),
            $this->forAllTest(),
            $this->selectAllTest(),
        );
    }
}
