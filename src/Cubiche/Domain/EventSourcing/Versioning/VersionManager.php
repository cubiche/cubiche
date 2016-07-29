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
     * @var Version
     */
    protected $currentApplicationVersion;

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
     * @param Version $applicationVersion
     */
    public static function setCurrentApplicationVersion(Version $applicationVersion)
    {
        static::create()->currentApplicationVersion = $applicationVersion;
    }

    /**
     * @param EventSourcedAggregateRootInterface $aggregate
     *
     * @return Version
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
     * @return Version
     */
    public static function currentApplicationVersion()
    {
        return static::create()->getCurrentApplicationVersion();
    }

    /**
     * @param string $className
     *
     * @return Version
     */
    public static function versionOfClass($className)
    {
        return static::create()->getAggregateRootVersion($className);
    }

    /**
     * @param string  $className
     * @param Version $version
     */
    public static function persistVersionOfClass($className, Version $version)
    {
        static::create()->setAggregateRootVersion($className, $version);
    }

    /**
     * @param string $aggregateClassName
     *
     * @return Version
     */
    protected function getAggregateRootVersion($aggregateClassName)
    {
        return $this->versionStore->loadAggregateRootVersion(
            $aggregateClassName,
            $this->getCurrentApplicationVersion()
        );
    }

    /**
     * @param string  $aggregateClassName
     * @param Version $version
     */
    protected function setAggregateRootVersion($aggregateClassName, Version $version)
    {
        $this->versionStore->persistAggregateRootVersion(
            $aggregateClassName,
            $version,
            $this->getCurrentApplicationVersion()
        );
    }

    /**
     * @return Version
     */
    protected function getCurrentApplicationVersion()
    {
        if ($this->currentApplicationVersion !== null) {
            return $this->currentApplicationVersion;
        }

        return $this->versionStore->loadApplicationVersion();
    }
}
