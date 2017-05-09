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

/**
 * MongoClientTestCaseTrait class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait MongoClientTestCaseTrait
{
    /**
     * @var \MongoClient
     */
    private $client;

    /**
     * @return \MongoClient
     */
    public function client()
    {
        if ($this->client === null) {
            $this->client = $this->createMongoClient();
        }

        return $this->client;
    }

    /**
     * @param string $testMethod
     */
    public function afterTestMethod($testMethod)
    {
        $collections = $this->client()->selectDB(DOCTRINE_MONGODB_DATABASE)->listCollections();
        foreach ($collections as $collection) {
            $collection->drop();
        }
    }

    /**
     * @return \MongoClient
     */
    protected function createMongoClient()
    {
        return new \MongoClient(DOCTRINE_MONGODB_SERVER);
    }

    /**
     * @return string
     */
    protected function getDatabaseName()
    {
        return DOCTRINE_MONGODB_DATABASE;
    }
}
