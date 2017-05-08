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

use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;

/**
 * VersionManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class VersionManager
{
    /**
     * @var VersionManager
     */
    private static $instance = null;

    /**
     * @var VersionStoreInterface
     */
    protected $versionStore;

    /**
     * VersionManager constructor.
     *
     * @param VersionStoreInterface $versionStore
     */
    private function __construct(VersionStoreInterface $versionStore)
    {
        $this->versionStore = $versionStore;
    }

    /**
     * @return VersionManager
     */
    public static function create()
    {
        if (static::$instance === null) {
            static::$instance = new static(new InMemoryVersionStore());
        }

        return static::$instance;
    }

    /**
     * @param VersionStoreInterface $versionStore
     */
    public static function setVersionStore(VersionStoreInterface $versionStore)
    {
        static::create()->versionStore = $versionStore;
    }

    /**
     * @param EventSourcedAggregateRootInterface $aggregate
     *
     * @return int
     */
    public static function versionOf(EventSourcedAggregateRootInterface $aggregate)
    {
        return static::versionOfClass(get_class($aggregate));
    }

    /**
     * @param EventSourcedAggregateRootInterface $aggregate
     */
    public static function persistVersionOf(EventSourcedAggregateRootInterface $aggregate)
    {
        static::persistVersionOfClass(get_class($aggregate), $aggregate->version());
    }

    /**
     * @param string $className
     *
     * @return int
     */
    public static function versionOfClass($className)
    {
        return static::create()->getAggregateRootVersion($className);
    }

    /**
     * @param string $className
     * @param int    $version
     */
    public static function persistVersionOfClass($className, $version)
    {
        static::create()->setAggregateRootVersion($className, $version);
    }

    /**
     * @param string $aggregateClassName
     *
     * @return int
     */
    protected function getAggregateRootVersion($aggregateClassName)
    {
        return $this->versionStore->load($aggregateClassName);
    }

    /**
     * @param string $aggregateClassName
     * @param int    $version
     */
    protected function setAggregateRootVersion($aggregateClassName, $version)
    {
        $this->versionStore->persist($aggregateClassName, $version);
    }
}
