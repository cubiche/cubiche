<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable;

/**
 * Cacheable Enumerable Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CacheableEnumerable extends EnumerableDecorator
{
    /**
     * @var EnumerableInterface
     */
    private $cache;

    /**
     * @param EnumerableInterface $enumerable
     */
    public function __construct(EnumerableInterface $enumerable)
    {
        parent::__construct($enumerable);

        $this->cache = null;
    }

    /**
     * {@inheritdoc}
     */
    public function enumerable()
    {
        return $this->isInitialized() ? $this->cache : $this->enumerable;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if (!$this->isInitialized()) {
            return $this->iterateAndCache();
        }

        return $this->cache->getIterator();
    }

    /**
     * Initialize the enumerable.
     */
    protected function isInitialized()
    {
        return $this->cache !== null;
    }

    /**
     * @return Generator
     */
    private function iterateAndCache()
    {
        $items = array();
        foreach ($this->enumerable() as $key => $value) {
            $items[$key] = $value;
            yield $key => $value;
        }

        $this->cache = Enumerable::from($items);
    }
}
