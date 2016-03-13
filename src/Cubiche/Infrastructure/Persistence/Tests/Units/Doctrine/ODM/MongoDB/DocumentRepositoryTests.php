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

use Cubiche\Domain\Collections\Tests\Units\CollectionTestCase;
use Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\DocumentRepository;
use Cubiche\Infrastructure\Persistence\Tests\Fixtures\User;
use Cubiche\Infrastructure\Persistence\Tests\Fixtures\UserId;
use Cubiche\Domain\Collections\Comparator\Sort;
use Cubiche\Domain\Specification\Criteria;

/**
 * Abstract Test Case Class.
 *
 * @engine isolate
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DocumentRepositoryTests extends CollectionTestCase
{
    use DocumentManagerTestCaseTrait;

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::randomCollection()
     */
    protected function randomCollection(array $items = array())
    {
        /** @var \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\DocumentRepository $repository */
        $repository = $this->emptyCollection();
        if (empty($items)) {
            foreach (range(0, rand(10, 20)) as $value) {
                $items[] = new User(UserId::next(), 'User-'.$value, rand(1, 100));
            }
        }
        $repository->addAll($items);

        return $repository;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::emptyCollection()
     */
    protected function emptyCollection()
    {
        return new DocumentRepository($this->dm()->getRepository(User::class));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::uniqueValue()
     */
    protected function uniqueValue()
    {
        return new User(UserId::next(), 'Methuselah', 1000);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::comparator()
     */
    protected function comparator()
    {
        return Sort::by(Criteria::property('age'));
    }
}
