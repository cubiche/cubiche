<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations;

use Cubiche\Domain\EventSourcing\Versioning\Version;

/**
 * Migration class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Migration
{
    /**
     * @var string[]
     */
    protected $aggregates;

    /**
     * @var Version
     */
    protected $version;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Migration constructor.
     *
     * @param string[]  $aggregates
     * @param Version   $version
     * @param \DateTime $createdAt
     */
    public function __construct(array $aggregates, Version $version, \DateTime $createdAt = null)
    {
        $this->aggregates = $aggregates;
        $this->version = $version;

        if ($createdAt === null) {
            $createdAt = new \DateTime();
        }
        $this->createdAt = $createdAt;
    }

    /**
     * @return string[]
     */
    public function aggregates()
    {
        return $this->aggregates;
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
