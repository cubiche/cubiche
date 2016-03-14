<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Tests\Units\Doctrine\ODM\MongoDB;

use Cubiche\Domain\Persistence\Tests\Fixtures\User;
use Cubiche\Domain\Persistence\Tests\Units\RepositoryTestCase;
use Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * Abstract Test Case Class.
 *
 * @engine isolate
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DocumentRepositoryTests extends RepositoryTestCase
{
    use DocumentManagerTestCaseTrait;

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Persistence\Tests\Units\RepositoryTestCase::emptyRepository()
     */
    protected function emptyRepository()
    {
        return new DocumentRepository($this->dm()->getRepository(User::class));
    }
}