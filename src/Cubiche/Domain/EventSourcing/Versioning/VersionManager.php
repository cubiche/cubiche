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

use Cubiche\Core\Serializer\DefaultSerializer;
use Cubiche\Core\Storage\InMemoryStorage;
use Cubiche\Core\Storage\StorageInterface;
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
     * @var StorageInterface
     */
    protected $storage;

    /**
     * VersionManager constructor.
     *
     * @param StorageInterface $storage
     */
    private function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return VersionManager
     */
    public static function create()
    {
        if (static::$instance === null) {
            static::$instance = new static(new InMemoryStorage(new DefaultSerializer()));
        }

        return static::$instance;
    }

    /**
     * @param StorageInterface $storage
     */
    public static function setStorage(StorageInterface $storage)
    {
        static::create()->storage = $storage;
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
     * @param string $className
     *
     * @return Version
     */
    public static function versionOfClass($className)
    {
        return static::create()->getVersion($className);
    }

    /**
     * @param string  $className
     * @param Version $version
     */
    public static function persistVersionOfClass($className, Version $version)
    {
        static::create()->setVersion($className, $version);
    }

    /**
     * @param string $aggregateClassName
     *
     * @return Version
     */
    protected function getVersion($aggregateClassName)
    {
        $key = $this->getKey($aggregateClassName);

        $version = $this->storage->get($key);
        if ($version === null) {
            $version = new Version();
            $this->setVersion($aggregateClassName, $version);
        }

        return $version;
    }

    /**
     * @param string  $aggregateClassName
     * @param Version $version
     */
    protected function setVersion($aggregateClassName, Version $version)
    {
        $this->storage->set($this->getKey($aggregateClassName), $version);
    }

    /**
     * @param string $aggregateClassName
     *
     * @return string
     */
    protected function getKey($aggregateClassName)
    {
        return strtolower(str_replace('\\', '_', $aggregateClassName));
    }
}
