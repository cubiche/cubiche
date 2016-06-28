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

use Cubiche\Domain\EventSourcing\Aggregate\Versioning\Version;
use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * Snapshot class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Snapshot
{
    /**
     * @var string
     */
    protected $aggregateType;

    /**
     * @var EventSourcedAggregateRootInterface
     */
    protected $aggregate;

    /**
     * @var Version
     */
    protected $version;

    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * Snapshot constructor.
     *
     * @param string                             $aggregateType
     * @param EventSourcedAggregateRootInterface $aggregate
     * @param \DateTimeImmutable                 $createdAt
     */
    public function __construct(
        $aggregateType,
        EventSourcedAggregateRootInterface $aggregate,
        \DateTimeImmutable $createdAt
    ) {
        $this->aggregateType = $aggregateType;
        $this->aggregate = $aggregate;
        $this->version = $aggregate->version();
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function aggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * @return IdInterface
     */
    public function aggregateId()
    {
        return $this->aggregate->id();
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
     * @return \DateTimeImmutable
     */
    public function createdAt()
    {
        return $this->createdAt;
    }
}
