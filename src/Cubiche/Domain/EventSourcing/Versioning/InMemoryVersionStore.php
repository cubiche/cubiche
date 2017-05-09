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
     * @param string $aggregateClassName
     * @param int    $version
     */
    public function persist($aggregateClassName, $version)
    {
        $this->store->set($aggregateClassName, $version);
    }

    /**
     * @param string $aggregateClassName
     *
     * @return int
     */
    public function load($aggregateClassName)
    {
        $version = $this->store->get($aggregateClassName);
        if ($version !== null) {
            return $version;
        }

        return 0;
    }
}
