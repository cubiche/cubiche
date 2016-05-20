<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\LazyCollection;

use Cubiche\Core\Collection\HashMapInterface;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * Lazy HashMap.
 *
 * @method HashMapInterface collection()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class LazyHashMap extends LazyCollection implements HashMapInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $this->lazyInitialize();

        return $this->collection()->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->lazyInitialize();

        $this->collection()->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key)
    {
        $this->lazyInitialize();

        return $this->collection()->containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function containsValue($value)
    {
        $this->lazyInitialize();

        return $this->collection()->containsValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAt($key)
    {
        $this->lazyInitialize();

        return $this->collection()->removeAt($key);
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        $this->lazyInitialize();

        return $this->collection()->keys();
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        $this->lazyInitialize();

        return $this->collection()->values();
    }

    /**
     * {@inheritdoc}
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        $this->lazyInitialize();

        $this->collection()->sort($criteria);
    }
}
