<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Snapshot;

use Cubiche\Core\Serializer\SerializableInterface;
use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;
use Cubiche\Domain\EventSourcing\Versioning\Version;

/**
 * Snapshot class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Snapshot implements SerializableInterface
{
    /**
     * @var string
     */
    protected $snapshotName;

    /**
     * @var EventSourcedAggregateRootInterface
     */
    protected $aggregate;

    /**
     * @var int
     */
    protected $version;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Snapshot constructor.
     *
     * @param string                             $snapshotName
     * @param EventSourcedAggregateRootInterface $aggregate
     */
    public function __construct($snapshotName, EventSourcedAggregateRootInterface $aggregate)
    {
        $this->snapshotName = $snapshotName;
        $this->aggregate = $aggregate;
        $this->version = $aggregate->version();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function snapshotName()
    {
        return $this->snapshotName;
    }

    /**
     * @return EventSourcedAggregateRootInterface
     */
    public function aggregate()
    {
        return $this->aggregate;
    }

    /**
     * @return Version
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * @return \DateTime
     */
    public function createdAt()
    {
        return $this->createdAt;
    }
}
