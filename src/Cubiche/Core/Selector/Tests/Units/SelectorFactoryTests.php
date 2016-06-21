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

use Cubiche\Core\Selector\Key;

/**
 * Selector Factory Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorFactoryTests extends SelectorFactoryInterfaceTestCase
{
    /**
     * Test __construct.
     */
    public function testConstruct()
    {
        $this
            /* @var \Cubiche\Core\Selector\SelectorFactoryInterface $factory */
            ->given($factory = $this->newTestedInstance('Cubiche\Core\Selector'))
            ->then()
                /* @var \Cubiche\Core\Selector\Key $key */
                ->object($key = $factory->create('key', array('foo')))
                    ->isInstanceOf(Key::class)
                ->string($key->name())
                    ->isEqualto('foo')
        ;
    }
}
