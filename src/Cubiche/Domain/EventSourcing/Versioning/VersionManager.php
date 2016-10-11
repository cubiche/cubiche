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
     * @return Version
     */
    public static function currentApplicationVersion()
    {
        return static::create()->getApplicationVersion();
    }

    /**
     * @param Version $applicationVersion
     */
    public static function setCurrentApplicationVersion(Version $applicationVersion)
    {
        static::create()->setApplicationVersion($applicationVersion);
    }

    /**
     * @param EventSourcedAggregateRootInterface $aggregate
     * @param Version                            $applicationVersion
     *
     * @return Version
     */
    public static function versionOf(EventSourcedAggregateRootInterface $aggregate, Version $applicationVersion = null)
    {
        return static::versionOfClass(get_class($aggregate), $applicationVersion);
    }

    /**
     * @param EventSourcedAggregateRootInterface $aggregate
     * @param Version                            $applicationVersion
     */
    public static function persistVersionOf(
        EventSourcedAggregateRootInterface $aggregate,
        Version $applicationVersion = null
    ) {
        static::persistVersionOfClass(get_class($aggregate), $aggregate->version(), $applicationVersion);
    }

    /**
     * @param string  $className
     * @param Version $applicationVersion
     *
     * @return Version
     */
    public static function versionOfClass($className, Version $applicationVersion = null)
    {
        return static::create()->getAggregateRootVersion($className, $applicationVersion);
    }

    /**
     * @param string  $className
     * @param Version $aggregateVersion
     * @param Version $applicationVersion
     */
    public static function persistVersionOfClass(
        $className,
        Version $aggregateVersion,
        Version $applicationVersion = null
    ) {
        static::create()->setAggregateRootVersion($className, $aggregateVersion, $applicationVersion);
    }

    /**
     * @param string  $aggregateClassName
     * @param Version $applicationVersion
     *
     * @return Version
     */
    protected function getAggregateRootVersion($aggregateClassName, Version $applicationVersion = null)
    {
        return $this->versionStore->loadAggregateRootVersion(
            $aggregateClassName,
            $this->getApplicationVersion($applicationVersion)
        );
    }

    /**
     * @param string  $aggregateClassName
     * @param Version $aggregateVersion
     * @param Version $applicationVersion
     */
    protected function setAggregateRootVersion(
        $aggregateClassName,
        Version $aggregateVersion,
        Version $applicationVersion = null
    ) {
        $this->versionStore->persistAggregateRootVersion(
            $aggregateClassName,
            $aggregateVersion,
            $this->getApplicationVersion($applicationVersion)
        );
    }

    /**
     * @param Version $applicationVersion
     *
     * @return Version
     */
    protected function getApplicationVersion(Version $applicationVersion = null)
    {
        if ($applicationVersion !== null) {
            return $applicationVersion;
        }

        return $this->versionStore->loadApplicationVersion();
    }

    /**
     * @param Version $applicationVersion
     */
    protected function setApplicationVersion(Version $applicationVersion)
    {
        $this->versionStore->persistApplicationVersion($applicationVersion);
    }
}
