<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Cache;

use Cubiche\Core\Metadata\ClassMetadata;

interface CacheInterface
{
    /**
     * Loads a class metadata instance from the cache.
     *
     * @param string $className
     *
     * @return ClassMetadata|null
     */
    public function load($className);

    /**
     * Puts a class metadata instance into the cache.
     *
     * @param ClassMetadata $metadata
     */
    public function save(ClassMetadata $metadata);
}
