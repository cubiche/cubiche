<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB;

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\ClassMetadataFactoryInterface;
use Cubiche\Core\Serializer\SerializerInterface;
use Cubiche\Domain\Identity\IdentifiableInterface;
use Cubiche\Infrastructure\MongoDB\Common\Connection;
use Cubiche\Infrastructure\MongoDB\Common\LoggableBulkWrite;
use Cubiche\Infrastructure\MongoDB\Common\QueryLoggerInterface;
use Cubiche\Infrastructure\MongoDB\Exception\MongoDBException;
use Cubiche\Infrastructure\MongoDB\QueryBuilder\QueryBuilder;
use MongoDB\Database;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\WriteConcern;
use Monolog\Logger;

/**
 * DocumentManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DocumentManager
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var ClassMetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * @var SchemaManager
     */
    protected $schemaManager;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var QueryLoggerInterface
     */
    protected $queryLogger;

    /**
     * Array of cached document database instances that are lazily loaded.
     *
     * @var array
     */
    protected $documentDatabases = array();

    /**
     * Array of cached document collection instances that are lazily loaded.
     *
     * @var array
     */
    protected $documentCollections = array();

    /**
     * Array of cached document name by collection.
     *
     * @var array
     */
    protected $documentNameByCollections = array();

    /**
     * DocumentManager constructor.
     *
     * @param Connection                    $connection
     * @param ClassMetadataFactoryInterface $metadataFactory
     * @param SerializerInterface           $serializer
     * @param Logger                        $logger
     * @param QueryLoggerInterface          $queryLogger
     */
    public function __construct(
        Connection $connection,
        ClassMetadataFactoryInterface $metadataFactory,
        SerializerInterface $serializer,
        Logger $logger,
        QueryLoggerInterface $queryLogger = null
    ) {
        $this->connection = $connection;
        $this->metadataFactory = $metadataFactory;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->queryLogger = $queryLogger;
        $this->schemaManager = new SchemaManager($this, $metadataFactory);
    }

    /**
     * Create a new QueryBuilder instance for a class.
     *
     * @param string $documentName
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($documentName)
    {
        return new QueryBuilder($this, $documentName);
    }

    /**
     * @param LoggableBulkWrite     $bulk
     * @param IdentifiableInterface $element
     */
    protected function addBulkUpdate(LoggableBulkWrite $bulk, IdentifiableInterface $element)
    {
        $bulk->update(
            array('_id' => $element->id()->toNative()),
            array('$set' => $this->serializer->serialize($element)),
            array('upsert' => true)
        );
    }

    /**
     * @param IdentifiableInterface $element
     */
    public function persist(IdentifiableInterface $element)
    {
        $bulk = new LoggableBulkWrite(
            $this->getDocumentCollection(get_class($element)),
            $this->queryLogger
        );

        $this->addBulkUpdate($bulk, $element);

        $this->execute($bulk);
    }

    /**
     * @param IdentifiableInterface[] $elements
     */
    public function persistAll(array $elements)
    {
        $bulk = new LoggableBulkWrite(
            $this->getDocumentCollection(get_class($elements[0])),
            $this->queryLogger
        );

        foreach ($elements as $element) {
            $this->addBulkUpdate($bulk, $element);
        }

        $this->execute($bulk);
    }

    /**
     * @param IdentifiableInterface $element
     */
    public function remove(IdentifiableInterface $element)
    {
        $bulk = new LoggableBulkWrite(
            $this->getDocumentCollection(get_class($element)),
            $this->queryLogger
        );

        $bulk->delete(array(
            '_id' => $element->id()->toNative(),
        ));

        $this->execute($bulk);
    }

    /**
     * @param LoggableBulkWrite $bulkWrite
     */
    protected function execute(LoggableBulkWrite $bulkWrite)
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $collection = $bulkWrite->collection();
        $manager = $collection->getManager();

        try {
            $manager->executeBulkWrite(
                $collection->getNamespace(),
                $bulkWrite->bulk(),
                $writeConcern
            );
        } catch (BulkWriteException $e) {
            $result = $e->getWriteResult();

            // Check if the write concern could not be fulfilled
            if ($writeConcernError = $result->getWriteConcernError()) {
                $this->logger->error(sprintf(
                    "%s (%s): %s\n",
                    $writeConcernError->getMessage(),
                    $writeConcernError->getCode(),
                    var_export($writeConcernError->getInfo(), true)
                ));
            }

            // Check if any write operations did not complete at all
            foreach ($result->getWriteErrors() as $writeError) {
                $this->logger->error(sprintf(
                    "Operation#%s: %s (%s)\n",
                    $writeError->getIndex(),
                    $writeError->getMessage(),
                    $writeError->getCode()
                ));
            }
        } catch (Exception $e) {
            $this->logger->error(sprintf(
                "Other error: %s\n",
                $e->getMessage()
            ));
            exit;
        }
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Returns the class metadata factory.
     *
     * @return ClassMetadataFactoryInterface
     */
    public function classMetadataFactory()
    {
        return $this->metadataFactory;
    }

    /**
     * Returns the metadata for a class.
     *
     * @param string $documentName
     *
     * @return ClassMetadata
     */
    public function getClassMetadata($documentName)
    {
        return $this->metadataFactory->getMetadataFor(ltrim($documentName, '\\'));
    }

    /**
     * @param array $query
     */
    protected function logQuery(array $query)
    {
        if ($this->queryLogger !== null) {
            $this->queryLogger->logQuery($query);
        }
    }

    /**
     * @return QueryLoggerInterface
     */
    public function queryLogger()
    {
        return $this->queryLogger;
    }

    /**
     * @return SchemaManager
     */
    public function getSchemaManager()
    {
        return $this->schemaManager;
    }

    /**
     * Returns the Collection instance for a class.
     *
     * @param string $documentName
     *
     * @return \MongoDB\Collection
     *
     * @throws MongoDBException
     */
    public function getDocumentCollection($documentName)
    {
        $documentName = ltrim($documentName, '\\');
        $metadata = $this->metadataFactory->getMetadataFor($documentName);

        if ($metadata == null) {
            throw MongoDBException::mappingNotFound($documentName);
        }

        $collectionName = $metadata->getMetadata('collection');
        if (!$collectionName) {
            throw MongoDBException::documentNotMappedToCollection($documentName);
        }

        if (!isset($this->documentCollections[$documentName])) {
            $database = $this->getDocumentDatabase($documentName);
            $this->documentCollections[$documentName] = $database->selectCollection($collectionName);
        }

        if (!isset($this->documentNameByCollections[$collectionName])) {
            $this->documentNameByCollections[$collectionName] = $documentName;
        }

        return $this->documentCollections[$documentName];
    }

    /**
     * Returns the Database instance for a class.
     *
     * @param string $documentName
     *
     * @return \MongoDB\Database
     */
    public function getDocumentDatabase($documentName)
    {
        $documentName = ltrim($documentName, '\\');
        if (isset($this->documentDatabases[$documentName])) {
            return $this->documentDatabases[$documentName];
        }

        $metadata = $this->metadataFactory->getMetadataFor($documentName);
        $databaseName = $metadata->getMetadata('database');
        $databaseName = $databaseName ?: $this->connection->database();
        $databaseName = $databaseName ?: 'default_database';

        $this->documentDatabases[$documentName] = new Database($this->connection->manager(), $databaseName);

        return $this->documentDatabases[$documentName];
    }

    /**
     * Returns the documentName by a given collection name.
     *
     * @param string $collectionName
     *
     * @return string
     *
     * @throws MongoDBException
     */
    public function getDocumentNameByCollection($collectionName)
    {
        if (!isset($this->documentNameByCollections[$collectionName])) {
            throw MongoDBException::documentNameNotFound($collectionName);
        }

        return $this->documentNameByCollections[$collectionName];
    }
}
