<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\QueryBuilder;

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Serializer\SerializerInterface;
use Cubiche\Infrastructure\MongoDB\Common\Cursor;
use Cubiche\Infrastructure\MongoDB\Common\QueryLoggerInterface;
use MongoDB\Collection;
use MongoDB\Driver\Cursor as MongoDBCursor;
use Traversable;

/**
 * Query class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Query
{
    const TYPE_FIND = 1;
    const TYPE_FIND_ONE = 2;
    const TYPE_REMOVE = 3;
    const TYPE_COUNT = 4;
    const TYPE_AGGREGATE = 5;

    /**
     * @var ClassMetadata
     */
    protected $classMetadata;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var Iterator
     */
    protected $iterator;

    /**
     * @var array
     */
    protected $querySettings;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var QueryLoggerInterface
     */
    protected $logger;

    /**
     * Query constructor.
     *
     * @param ClassMetadata        $classMetadata
     * @param SerializerInterface  $serializer
     * @param Collection           $collection
     * @param array                $querySettings
     * @param array                $options
     * @param QueryLoggerInterface $logger
     */
    public function __construct(
        ClassMetadata $classMetadata,
        SerializerInterface $serializer,
        Collection $collection,
        array $querySettings = [],
        array $options = [],
        QueryLoggerInterface $logger = null
    ) {
        $this->classMetadata = $classMetadata;
        $this->serializer = $serializer;
        $this->collection = $collection;
        $this->querySettings = $querySettings;
        $this->options = $options;
        $this->logger = $logger;
        $this->type = self::TYPE_FIND;
    }

    /**
     * @return mixed|MongoDBCursor
     *
     * @throws \Exception
     */
    public function execute()
    {
        switch ($this->type) {
            case self::TYPE_FIND:
                $this->logQuery(array(
                    'collection' => $this->collection,
                    'operation' => 'find',
                    'parameters' => array($this->querySettings, $this->options),
                ));

                return new Cursor(
                    $this->collection->find($this->querySettings, $this->options)->toArray(),
                    $this->serializer,
                    $this->classMetadata
                );
                break;
            case self::TYPE_REMOVE:
                $this->logQuery(array(
                    'collection' => $this->collection,
                    'operation' => 'deleteMany',
                    'parameters' => array($this->querySettings, $this->options),
                ));

                $this->collection->deleteMany($this->querySettings, $this->options);
                break;
            case self::TYPE_COUNT:
                $this->logQuery(array(
                    'collection' => $this->collection,
                    'operation' => 'count',
                    'parameters' => array($this->querySettings, $this->options),
                ));

                return $this->collection->count($this->querySettings, $this->options);
                break;
            case self::TYPE_AGGREGATE:
                $this->logQuery(array(
                    'collection' => $this->collection,
                    'operation' => 'aggregate',
                    'parameters' => array($this->querySettings, $this->options),
                ));

                return $this->collection->aggregate($this->querySettings, $this->options);
                break;
            default:
                throw new \Exception('Unsupported query type ... I\'m sorry');
        }
    }

    /**
     * @return array|object|null
     */
    public function getSingleResult()
    {
        return $this->getIterator()->getSingleResult();
    }

    /**
     * @return int
     */
    public function count()
    {
        $this->type = self::TYPE_COUNT;

        return $this->execute();
    }

    /**
     * @return Traversable
     */
    public function aggregate()
    {
        $this->type = self::TYPE_AGGREGATE;

        return $this->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function remove()
    {
        $this->type = self::TYPE_REMOVE;

        $this->execute();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->getIterator()->toArray();
    }

    /**
     * @return Cursor
     */
    public function getIterator()
    {
        switch ($this->type) {
            case self::TYPE_FIND:
                break;

            default:
                throw new \BadMethodCallException('Iterator would not be returned for query type: '.$this->type);
        }

        if ($this->iterator === null) {
            $cursor = $this->execute();

            if (!$cursor instanceof Cursor) {
                throw new \UnexpectedValueException('Iterator was not returned from executed query');
            }

            $this->iterator = $cursor;
        }

        return $this->iterator;
    }

    /**
     * @return array
     */
    public function getQuerySettings()
    {
        return $this->querySettings;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $query
     */
    protected function logQuery(array $query)
    {
        if ($this->logger !== null) {
            $this->logger->logQuery($query);
        }
    }
}
