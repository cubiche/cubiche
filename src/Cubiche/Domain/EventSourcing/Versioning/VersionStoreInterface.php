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

/**
 * VersionStore interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface VersionStoreInterface
{
    /**
     * @param string  $aggregateClassName
     * @param Version $aggregateRootVersion
     * @param Version $applicationVersion
     */
    public function persistAggregateRootVersion(
        $aggregateClassName,
        Version $aggregateRootVersion,
        Version $applicationVersion
    );

    /**
     * @param string  $aggregateClassName
     * @param Version $applicationVersion
     *
     * @return Version
     */
    public function loadAggregateRootVersion($aggregateClassName, Version $applicationVersion);

    /**
     * @param Version $applicationVersion
     */
    public function persistApplicationVersion(Version $applicationVersion);

    /**
     * @return Version
     */
    public function loadApplicationVersion();
}
