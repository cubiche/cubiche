<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Tests\Doctrine\ODM\MongoDB;

use Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\DocumentRepository;
use Cubiche\Infrastructure\Persistence\Tests\Doctrine\ODM\MongoDB\Documents\Document;
use Cubiche\Domain\Collections\Specification\Criteria;

/**
 * Document Repository Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DocumentRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function testFind()
    {
        $respository = new DocumentRepository($this->dm->getRepository(Document::class));
        for ($i = 1; $i <= 10; ++$i) {
            $respository->add(new Document('foo'.$i, $i));
        }

        $this->findTest($respository, Criteria::method('intValue')->gt(8));
    }

    /**
     * @test
     */
    public function testFindAndSlice()
    {
        $respository = new DocumentRepository($this->dm->getRepository(Document::class));

        for ($i = 1; $i <= 10; ++$i) {
            $respository->add(new Document('foo'.$i, $i));
        }

        $criteria = Criteria::method('intValue')->gt(3);
        $this->findAndSliceTest($respository, $criteria, 2, 4);
        $this->findAndSliceTest($respository, $criteria, 0, 5);
        $this->findAndSliceTest($respository, $criteria, 4, 4);
        $this->findAndSliceTest($respository, $criteria, 8, 4);
    }
}
