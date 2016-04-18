<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Repository\Tests\Units;

use Cubiche\Domain\Repository\InMemoryRepository;
use Cubiche\Domain\Repository\Tests\Fixtures\User;
use Cubiche\Domain\Repository\Tests\Fixtures\UserId;

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
     * @see \Cubiche\Domain\Repository\Tests\Units\RepositoryTestCase::emptyRepository()
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
