<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector\Tests\Units;

/**
 * Property Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PropertyTests extends FieldTestCase
{
    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            /* @var \Cubiche\Core\Selector\Property $property */
            ->given($property = $this->newTestedInstance('foo'))
            ->then()
                ->string($property->apply((object) array('foo' => 'bar')))
                    ->isEqualTo('bar')
                ->exception(function () use ($property) {
                    $property->apply(null);
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($property) {
                    $property->apply((object) array());
                })->isInstanceOf(\RuntimeException::class)
        ;
    }
}
