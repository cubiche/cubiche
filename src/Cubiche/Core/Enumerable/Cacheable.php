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
 * Cacheable trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait Cacheable
{
    /**
     * @var \Iterator
     */
    private $innerIterator = null;

    /**
     * @var mixed[]
     */
    private $cache = null;

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if (!$this->initialized()) {
            $this->initialize(parent::getIterator());

            return $this->iterateAndCache();
        }

        return new \ArrayIterator($this->cache);
    }

    /**
     * @return bool
     */
    private function initialized()
    {
        return $this->innerIterator === null && $this->cache !== null;
    }

    /**
     * @param \Iterator $iterator
     */
    private function initialize(\Iterator $iterator)
    {
        if ($this->innerIterator === null && $this->cache === null) {
            $this->cache = array();
            $this->innerIterator = $iterator;
            $this->innerIterator->rewind();
        }
    }

    /**
     * @return Generator
     */
    private function iterateAndCache()
    {
        foreach ($this->cache as $key => $value) {
            yield $key => $value;
        }
        if ($this->innerIterator !== null) {
            while ($this->innerIterator->valid()) {
                $this->cache[$this->innerIterator->key()] = $this->innerIterator->current();
                yield $this->innerIterator->key() => $this->innerIterator->current();
                $this->innerIterator->next();
            }

            $this->innerIterator = null;
        }
    }
}
