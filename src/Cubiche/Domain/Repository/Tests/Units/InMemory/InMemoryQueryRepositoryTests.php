<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Repository\Tests\Units\InMemory;

use Cubiche\Domain\Repository\InMemory\InMemoryQueryRepository;
use Cubiche\Domain\Repository\Tests\Fixtures\User;
use Cubiche\Domain\Repository\Tests\Fixtures\UserId;
use Cubiche\Domain\Repository\Tests\Units\QueryRepositoryTestCase;

/**
 * InMemoryQueryRepositoryTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class InMemoryQueryRepositoryTests extends QueryRepositoryTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function emptyRepository()
    {
        return new InMemoryQueryRepository(User::class);
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->exception(function () {
                new InMemoryQueryRepository(UserId::class);
            })
            ->isInstanceOf(\LogicException::class)
        ;
    }
}
