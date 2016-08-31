<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations\Store;

use Cubiche\Core\Collections\ArrayCollection\SortedArrayHashMap;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ReverseComparator;
use Cubiche\Domain\EventSourcing\Migrations\Migration;
use Cubiche\Domain\EventSourcing\Versioning\Version;

/**
 * InMemoryMigrationStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryMigrationStore implements MigrationStoreInterface
{
    /**
     * @var SortedArrayHashMap
     */
    protected $store;

    /**
     * InMemoryMigrationStore constructor.
     */
    public function __construct()
    {
        $this->store = new SortedArrayHashMap([], new ReverseComparator(new Comparator()));
    }

    /**
     * @param Migration $migration
     */
    public function persist(Migration $migration)
    {
        $this->store->set($migration->version()->toInt(), $migration);
    }

    /**
     * @param Version $version
     *
     * @return bool
     */
    public function hasVersion(Version $version)
    {
        return $this->store->containsKey($version->toInt());
    }

    /**
     * @return Migration[]
     */
    public function findAll()
    {
        return $this->store->values()->toArray();
    }

    /**
     * @return Migration
     */
    public function getLast()
    {
        return reset($this->findAll());
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->store->count();
    }
}
