<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections;

use Cubiche\Domain\Collections\Specification\SpecificationInterface;

/**
 * Collection Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface CollectionInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @param mixed $item
     */
    public function add($item);

    /**
     * @param mixed $item
     */
    public function remove($item);

    public function clear();

    /**
     * @param mixed $item
     *
     * @return bool
     */
    public function contains($item);

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param SpecificationInterface $specification
     *
     * @return CollectionInterface
     */
    public function find(SpecificationInterface $specification);

    /**
     * @return array
     */
    public function toArray();

    /**
     * @param int $offset
     * @param int $length
     *
     * @return CollectionInterface
     */
    public function slice($offset, $length = null);
}
