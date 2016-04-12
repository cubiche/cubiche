<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Repository\Tests\Units\Doctrine\ODM\MongoDB;

use Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\EventSubscriber as CollectionsEventSubscriber;
use Cubiche\Infrastructure\Identity\Doctrine\ODM\MongoDB\EventSubscriber as IdentityEventSubscriber;
use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\EventSubscriber as ModelEventSubscriber;
use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Cubiche\Infrastructure\Repository\Tests\Units\Doctrine\ODM\MongoDB\Types\PhonenumberType;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
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
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function dm()
    {
        if ($this->dm === null) {
            $this->dm = $this->createTestDocumentManager();
            $this->uow = $this->dm->getUnitOfWork();

            Type::addType('Phonenumber', PhonenumberType::class);

            $this->dm->getEventManager()->addEventSubscriber(new ModelEventSubscriber());
            $this->dm->getEventManager()->addEventSubscriber(new IdentityEventSubscriber());
            $this->dm->getEventManager()->addEventSubscriber(new CollectionsEventSubscriber());
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
        $config->setClassMetadataFactoryName(ClassMetadataFactory::class);
        $config->setMetadataDriverImpl($this->createMetadataDriverImpl());
        $config->setMetadataCacheImpl(new FilesystemCache(__DIR__.'/Cache'));

        return $config;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Mapping\Driver\SimplifiedXmlDriver
     */
    protected function createMetadataDriverImpl()
    {
        $prefixs = array(
          __DIR__.'/mapping' => 'Cubiche\Domain\Repository\Tests\Fixtures',
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
