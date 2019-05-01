<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Tests\Units;

use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\Metadata\Cache\FileCache;
use Cubiche\Core\Metadata\ClassMetadataFactory;
use Cubiche\Core\Metadata\Locator\DefaultFileLocator;
use Cubiche\Core\Metadata\Tests\Fixtures\Driver\XmlDriver;
use Cubiche\Core\Serializer\Handler\CollectionHandler;
use Cubiche\Core\Serializer\Handler\CoordinateHandler;
use Cubiche\Core\Serializer\Handler\DateRangeHandler;
use Cubiche\Core\Serializer\Handler\DateTimeHandler;
use Cubiche\Core\Serializer\Handler\DateTimeValueObjectHandler;
use Cubiche\Core\Serializer\Handler\HandlerManager;
use Cubiche\Core\Serializer\Handler\LocalizableValueHandler;
use Cubiche\Core\Serializer\Serializer;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;
use Cubiche\Core\Serializer\Visitor\VisitorNavigator;
use Cubiche\Infrastructure\MongoDB\Common\Connection;
use Cubiche\Infrastructure\MongoDB\DocumentManager;
use MongoDB\Database;
use MongoDB\Driver\WriteConcern;
use MongoDB\Operation\DropCollection;
use Monolog\Logger;

/**
 * Document Manager Test Case Trait.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait DocumentManagerTestCaseTrait
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var string
     */
    protected $cacheDirectory = __DIR__.'/Cache';

    /**
     * Create the cache directory.
     */
    public function setUp()
    {
        if (!is_dir($this->cacheDirectory)) {
            mkdir($this->cacheDirectory);
        }
    }

    /**
     * Remove the cache directory.
     */
    public function tearDown()
    {
        $this->rmdir($this->cacheDirectory);
        $this->database()->drop();
    }

    /**
     * @return DocumentManager
     */
    public function dm()
    {
        if ($this->dm === null) {
            $this->dm = $this->createDocumentManager();
        }

        return $this->dm;
    }

    /**
     * @param string $testMethod
     */
    public function afterTestMethod($testMethod)
    {
        $database = $this->database();
        /** @var CollectionInfo $collection */
        foreach ($database->listCollections() as $collection) {
            $operation = new DropCollection(
                $database->getDatabaseName(),
                $collection->getName(),
                ['writeConcern' => new WriteConcern(WriteConcern::MAJORITY, 1000)]
            );

            $operation->execute($database->getManager()->getServers()[0]);
        }
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return new Connection(MONGODB_SERVER, $this->databaseName());
    }

    /**
     * @return Database
     */
    protected function database()
    {
        $connection = $this->getConnection();

        return new Database($connection->manager(), $connection->database());
    }

    /**
     * @return string
     */
    protected function databaseName()
    {
        return MONGODB_DATABASE;
    }

    /**
     * @return DocumentManager
     */
    protected function createDocumentManager()
    {
        $connection = $this->getConnection();
        $metadataFactory = $this->createMetadataFactory();
        $serializer = $this->createSerializer();
        $logger = new Logger('inline_logger');

        return new DocumentManager($connection, $metadataFactory, $serializer, $logger);
    }

    /**
     * @return Serializer
     */
    protected function createSerializer()
    {
        $metadataFactory = $this->createMetadataFactory();

        $handlerManager = new HandlerManager();
        $eventBus = EventBus::create();

        // handlers
        $collectionHandler = new CollectionHandler();
        $dateHandler = new DateTimeHandler();
        $coordinateHandler = new CoordinateHandler();
        $localizableValueHandler = new LocalizableValueHandler();
        $dateTimeValueObjectHandler = new DateTimeValueObjectHandler();
        $dateRangeHandler = new DateRangeHandler();

        $handlerManager->addHandler($collectionHandler);
        $handlerManager->addHandler($dateHandler);
        $handlerManager->addHandler($coordinateHandler);
        $handlerManager->addHandler($localizableValueHandler);
        $handlerManager->addHandler($dateTimeValueObjectHandler);
        $handlerManager->addHandler($dateRangeHandler);

        $navigator = new VisitorNavigator($metadataFactory, $handlerManager, $eventBus);
        $serializationVisitor = new SerializationVisitor($navigator);
        $deserializationVisitor = new DeserializationVisitor($navigator);

        return new Serializer($navigator, $serializationVisitor, $deserializationVisitor);
    }

    /**
     * @return XmlDriver
     */
    protected function createDriver()
    {
        $mappingDirectory = __DIR__.'/../Fixtures/mapping';

        return new XmlDriver(
            new DefaultFileLocator([
                $mappingDirectory => 'Cubiche\Infrastructure\MongoDB\Tests\Fixtures',
            ])
        );
    }

    /**
     * @return FileCache
     */
    protected function createCache()
    {
        return new FileCache($this->cacheDirectory);
    }

    /**
     * @return ClassMetadataFactory
     */
    protected function createMetadataFactory()
    {
        return new ClassMetadataFactory($this->createDriver(), $this->createCache());
    }

    /**
     * Remove directory when the directory is not empty.
     *
     * @param string $dir
     */
    protected function rmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($dir.'/'.$object) == 'dir') {
                        $this->rmdir($dir.'/'.$object);
                    } else {
                        unlink($dir.'/'.$object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }
}
