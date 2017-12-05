<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Common;

use MongoDB\Collection;
use MongoDB\Driver\BulkWrite;

/**
 * LoggableBulkWrite class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LoggableBulkWrite
{
    /**
     * @var QueryLoggerInterface
     */
    protected $logger;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var BulkWrite
     */
    protected $bulk;

    /**
     * LoggableBulkWrite constructor.
     *
     * @param Collection           $collection
     * @param QueryLoggerInterface $logger
     */
    public function __construct(Collection $collection, QueryLoggerInterface $logger = null)
    {
        $this->collection = $collection;
        $this->logger = $logger;
        $this->bulk = new BulkWrite(['ordered' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($filter, array $deleteOptions = [])
    {
        $this->bulk->delete($filter, $deleteOptions);

        $this->logQuery(array(
            'collection' => $this->collection,
            'operation' => 'BulkDelete',
            'parameters' => array($filter, $deleteOptions),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function insert($document)
    {
        $this->bulk->insert($document);

        $this->logQuery(array(
            'collection' => $this->collection,
            'operation' => 'BulkInsert',
            'parameters' => array($document),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function update($filter, $newObj, array $updateOptions = [])
    {
        $this->bulk->update($filter, $newObj, $updateOptions);

        $this->logQuery(array(
            'collection' => $this->collection,
            'operation' => 'BulkUpdate',
            'parameters' => array($filter, $newObj, $updateOptions),
        ));
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

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * @return mixed
     */
    public function bulk()
    {
        return $this->bulk;
    }
}
