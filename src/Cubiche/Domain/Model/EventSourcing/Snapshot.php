<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\EventSourcing;

use Cubiche\Core\Serializer\SerializableInterface;
use Cubiche\Domain\Model\AggregateRootInterface;

/**
 * Snapshot class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Snapshot implements SerializableInterface
{
    /**
     * @var int
     */
    protected $version;

    /**
     * @var AggregateRootInterface
     */
    protected $aggregate;

    /**
     * Snapshot constructor.
     *
     * @param int                    $version
     * @param AggregateRootInterface $aggregate
     */
    public function __construct($version, AggregateRootInterface $aggregate)
    {
        $this->version = $version;
        $this->aggregate = $aggregate;
    }

    /**
     * @return string
     */
    public function className()
    {
        return get_class($this->aggregate());
    }

    /**
     * @return int
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * @return AggregateRootInterface
     */
    public function aggregate()
    {
        return $this->aggregate;
    }
}
