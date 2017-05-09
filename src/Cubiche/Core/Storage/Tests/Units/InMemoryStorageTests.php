<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Storage\Tests\Units;

use Cubiche\Core\Serializer\DefaultSerializer;
use Cubiche\Core\Storage\InMemoryStorage;
use Cubiche\Core\Storage\StorageInterface;

/**
 * InMemoryStorageTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryStorageTests extends StorageTestCase
{
    /**
     * Test get method.
     */
    public function testCreate()
    {
        $this
            ->given($storage = new InMemoryStorage(new DefaultSerializer(), array('foo' => 'bar')))
            ->then()
                ->string($storage->get('foo'))
                    ->isEqualTo('bar')
        ;
    }

    /**
     * @return StorageInterface
     */
    protected function createStorage()
    {
        return new InMemoryStorage(new DefaultSerializer());
    }
}
