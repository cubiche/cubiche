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

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
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
     * {@inheritdoc}
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->dm = $this->createTestDocumentManager();
        $this->uow = $this->dm->getUnitOfWork();

        AnnotationDriver::registerAnnotationClasses();
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
