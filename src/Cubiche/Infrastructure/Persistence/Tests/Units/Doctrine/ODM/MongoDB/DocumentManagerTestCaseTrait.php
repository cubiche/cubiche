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

use Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\EventListener;
use Cubiche\Infrastructure\Persistence\Tests\Units\Doctrine\ODM\MongoDB\Types\UserIdType;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\ODM\MongoDB\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ODM\MongoDB\Types\Type;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * Document Manager Test Case Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait DocumentManagerTestCaseTrait
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
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function dm()
    {
        if ($this->dm === null) {
            $this->dm = $this->createTestDocumentManager();
            $this->uow = $this->dm->getUnitOfWork();
            $this->lastQuery = null;

            Type::registerType('UserId', UserIdType::class);

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

        return $this->dm;
    }

    /**
     * @param string $testMethod
     */
    public function afterTestMethod($testMethod)
    {
        $this->dm()->clear();

        $collections = $this->dm()->getConnection()->selectDatabase(DOCTRINE_MONGODB_DATABASE)->listCollections();
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
     * @return \Doctrine\ODM\MongoDB\Mapping\Driver\SimplifiedXmlDriver
     */
    protected function createMetadataDriverImpl()
    {
        $prefixs = array(
          __DIR__.'/mapping' => 'Cubiche\Domain\Persistence\Tests\Fixtures',
        );

        return new SimplifiedXmlDriver($prefixs);
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
}
