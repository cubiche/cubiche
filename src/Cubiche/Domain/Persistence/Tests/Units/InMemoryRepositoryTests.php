<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Persistence\Tests\Units;

use Cubiche\Domain\Persistence\InMemoryRepository;
use Cubiche\Domain\Persistence\Tests\Fixtures\User;
use Cubiche\Domain\Persistence\Tests\Fixtures\UserId;

/**
 * In Memory Repository Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class InMemoryRepositoryTests extends RepositoryTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Persistence\Tests\Units\RepositoryTestCase::emptyRepository()
     */
    protected function emptyRepository()
    {
        return new InMemoryRepository(User::class);
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->exception(function () {
                new InMemoryRepository(UserId::class);
            })
            ->isInstanceOf(\LogicException::class)
        ;
    }
}
