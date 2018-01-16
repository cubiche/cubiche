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

use MongoDB\Driver\Manager;

/**
 * Connection class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Connection
{
    /**
     * Server string connection.
     *
     * @var string
     */
    protected $server;

    /**
     * Default DB to use for all documents.
     *
     * @var string
     */
    protected $database;

    /**
     * MongoDB manager.
     *
     * @var Manager
     */
    protected $manager;

    /**
     * Connection constructor.
     *
     * @param string $server
     * @param string $database
     */
    public function __construct($server, $database = 'test')
    {
        $this->server = $server;
        $this->database = $database;
        $this->manager = new Manager($server);
    }

    /**
     * @return string
     */
    public function server()
    {
        return $this->server;
    }

    /**
     * @return string
     */
    public function database()
    {
        return $this->database;
    }

    /**
     * @return Manager
     */
    public function manager()
    {
        return $this->manager;
    }
}
