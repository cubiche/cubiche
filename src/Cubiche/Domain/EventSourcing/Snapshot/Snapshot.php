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

use Cubiche\Domain\EventSourcing\AggregateRootInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * Snapshot class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Snapshot implements SnapshotInterface
{
    /**
     * @var IdInterface
     */
    protected $id;

    /**
     * @var AggregateRootInterface
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
     * @param IdInterface            $id
     * @param AggregateRootInterface $aggregate
     */
    public function __construct(IdInterface $id, AggregateRootInterface $aggregate)
    {
        $this->id = $id;
        $this->aggregate = $aggregate;
        $this->version = $aggregate->version();
        $this->createdAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(IdInterface $id)
    {
        $this->id = $id;
    }

    /**
     * @return AggregateRootInterface
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
