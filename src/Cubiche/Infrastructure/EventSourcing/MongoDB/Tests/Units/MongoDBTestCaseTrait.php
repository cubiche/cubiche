<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\EventSourcing\MongoDB\Tests\Units;

use Cubiche\Infrastructure\MongoDB\Common\Connection;
use MongoDB\Database;
use MongoDB\Driver\WriteConcern;
use MongoDB\Model\CollectionInfo;
use MongoDB\Operation\DropCollection;

/**
 * MongoDBTestCaseTrait class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait MongoDBTestCaseTrait
{
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
    protected function databaseName()
    {
        return MONGODB_DATABASE;
    }

    /**
     * Remove the database.
     */
    public function tearDown()
    {
        $this->database()->drop();
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
}
