<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Versioning;

use Cubiche\Core\Serializer\SerializableInterface;

/**
 * Version class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Version implements SerializableInterface
{
    /**
     * @var int
     */
    protected $modelVersion;

    /**
     * @var int
     */
    protected $aggregateVersion;

    /**
     * Version constructor.
     *
     * @param int $modelVersion
     * @param int $aggregateVersion
     */
    public function __construct($modelVersion = 0, $aggregateVersion = 0)
    {
        $this->setModelVersion($modelVersion);
        $this->setAggregateVersion($aggregateVersion);
    }

    /**
     * @return int
     */
    public function modelVersion()
    {
        return $this->modelVersion;
    }

    /**
     * @param int $modelVersion
     */
    public function setModelVersion($modelVersion)
    {
        $this->modelVersion = $modelVersion;
    }

    /**
     * Increment the model version.
     */
    public function incModelVersion()
    {
        ++$this->modelVersion;
    }

    /**
     * @return int
     */
    public function aggregateVersion()
    {
        return $this->aggregateVersion;
    }

    /**
     * @param int $aggregateVersion
     */
    public function setAggregateVersion($aggregateVersion)
    {
        $this->aggregateVersion = $aggregateVersion;
    }

    /**
     * Increment the aggregate version.
     */
    public function incAggregateVersion()
    {
        ++$this->aggregateVersion;
    }
}
