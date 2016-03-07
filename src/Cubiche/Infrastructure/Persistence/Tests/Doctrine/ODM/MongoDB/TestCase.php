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

use Cubiche\Domain\Collections\Tests\CollectionTestCase;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Events;
use Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\EventListener;

/**
 * Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class TestCase extends CollectionTestCase
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var UnitOfWork
     */
    protected $uow;

    /**
     * @var array
     */
    private $lastQuery;

    /**
     * {@inheritdoc}
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->dm = $this->createTestDocumentManager();
        $this->uow = $this->dm->getUnitOfWork();
        $this->lastQuery = null;

        AnnotationDriver::registerAnnotationClasses();

        $events = array(
            Events::prePersist,
            Events::postPersist,
            Events::preUpdate,
            Events::postUpdate,
            Events::preLoad,
            Events::postLoad,
            Events::preRemove,
            Events::postRemove,
            Events::preFlush,
            Events::onFlush,
            Events::postFlush,
            Events::loadClassMetadata,
        );

        $this->dm->getEventManager()->addEventListener($events, new EventListener());
    }

    /**
     * {@inheritdoc}
     *
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        if (!$this->dm) {
            return;
        }

        $collections = $this->dm->getConnection()->selectDatabase(DOCTRINE_MONGODB_DATABASE)->listCollections();
        foreach ($collections as $collection) {
            $collection->drop();
        }
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Configuration
     */
    protected function getConfiguration()
    {
        $config = new Configuration();
        $config->setProxyDir(__DIR__.'/Proxies');
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir(__DIR__.'/Hydrators');
        $config->setHydratorNamespace('Hydrators');
        $config->setDefaultDB(DOCTRINE_MONGODB_DATABASE);
        $config->setMetadataDriverImpl($this->createMetadataDriverImpl());

        $config->setLoggerCallable(function (array $log) {
            $this->lastQuery = $log;
        });

        return $config;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver
     */
    protected function createMetadataDriverImpl()
    {
        return AnnotationDriver::create(__DIR__.'/Documents');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function createTestDocumentManager()
    {
        $config = $this->getConfiguration();
        $connection = new Connection(DOCTRINE_MONGODB_SERVER, array(), $config);

        return DocumentManager::create($connection, $config);
    }

    /**
     * @return array
     */
    protected function lastQuery()
    {
        return $this->lastQuery;
    }

    /**
     * @return mixed
     */
    protected function getServerVersion()
    {
        $result = $this->dm->getConnection()->selectDatabase(DOCTRINE_MONGODB_DATABASE)->command(array(
            'buildInfo' => 1,
        ));

        return $result['version'];
    }
}
