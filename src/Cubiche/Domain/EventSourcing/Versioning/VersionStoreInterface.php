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
     * @param string $aggregateClassName
     * @param int    $version
     */
    public function persist($aggregateClassName, $version);

    /**
     * @param string $aggregateClassName
     *
     * @return int
     */
    public function load($aggregateClassName);
}
