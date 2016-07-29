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

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;

/**
 * InMemoryVersionStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryVersionStore implements VersionStoreInterface
{
    /**
     * Key used to store the current application version in storage.
     */
    const APPLICATION_VERSION_KEY = '_current_application_version';

    /**
     * @var ArrayHashMap
     */
    protected $store;

    /**
     * InMemoryVersionStore constructor.
     */
    public function __construct()
    {
        $this->store = new ArrayHashMap();
    }

    /**
     * @param string  $aggregateClassName
     * @param Version $aggregateRootVersion
     * @param Version $applicationVersion
     */
    public function persistAggregateRootVersion(
        $aggregateClassName,
        Version $aggregateRootVersion,
        Version $applicationVersion = null
    ) {
        $applicationKey = $this->getApplicationKey($applicationVersion);
        if (!$this->store->containsKey($applicationKey)) {
            $this->store->set($applicationKey, new ArrayHashMap());
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);

        $aggregateRootKey = $this->getAggregateRootKey($aggregateClassName);
        $applicationCollection->set($aggregateRootKey, $aggregateRootVersion);
    }

    /**
     * @param string  $aggregateClassName
     * @param Version $applicationVersion
     *
     * @return Version
     */
    public function loadAggregateRootVersion($aggregateClassName, Version $applicationVersion = null)
    {
        $applicationKey = $this->getApplicationKey($applicationVersion);
        if (!$this->store->containsKey($applicationKey)) {
            $this->store->set($applicationKey, new ArrayHashMap());
        }

        /** @var ArrayHashMap $applicationCollection */
        $applicationCollection = $this->store->get($applicationKey);

        $aggregateRootKey = $this->getAggregateRootKey($aggregateClassName);
        if (!$applicationCollection->containsKey($aggregateRootKey)) {
            return Version::fromString('0.0.0');
        }

        return $applicationCollection->get($aggregateRootKey);
    }

    /**
     * @param Version $applicationVersion
     */
    public function persistApplicationVersion(Version $applicationVersion)
    {
        $this->store->set(self::APPLICATION_VERSION_KEY, $applicationVersion);
    }

    /**
     * @return Version
     */
    public function loadApplicationVersion()
    {
        if (!$this->store->containsKey(self::APPLICATION_VERSION_KEY)) {
            return Version::fromString('0.0.0');
        }

        return $this->store->get(self::APPLICATION_VERSION_KEY);
    }

    /**
     * @param Version $applicationVersion
     *
     * @return string
     */
    protected function getApplicationKey(Version $applicationVersion = null)
    {
        if ($applicationVersion === null) {
            $applicationVersion = $this->loadApplicationVersion();
        }

        return $applicationVersion->__toString();
    }

    /**
     * @param string $aggregateClassName
     *
     * @return string
     */
    protected function getAggregateRootKey($aggregateClassName)
    {
        return $aggregateClassName;
    }
}
