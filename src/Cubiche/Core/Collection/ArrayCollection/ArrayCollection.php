<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\ArrayCollection;

use Cubiche\Core\Collection\CollectionInterface;
use Cubiche\Core\Collection\Exception\InvalidKeyException;

/**
 * ArrayCollection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class ArrayCollection implements CollectionInterface, \ArrayAccess
{
    /**
     * @var array
     */
    protected $elements = array();

    /**
     * AbstractArrayCollection constructor.
     *
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->elements = $elements;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->elements = array();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * {@inheritdoc}
     */
    protected function hasKey($key)
    {
        $this->validateKey($key);

        return isset($this->elements[$key]) || \array_key_exists($key, $this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->hasKey($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $this->validateKey($offset);

        return isset($this->elements[$offset]) ? $this->elements[$offset] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->validateKey($offset);

        $this->elements[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if ($this->hasKey($offset)) {
            unset($this->elements[$offset]);

            $this->elements = array_values($this->elements);
        }
    }

    /**
     * Validates that a key is valid.
     *
     * @param int|string $key
     *
     * @return bool
     *
     * @throws InvalidKeyException If the key is invalid.
     */
    protected function validateKey($key)
    {
        if (!is_string($key) && !is_int($key)) {
            throw InvalidKeyException::forKey($key);
        }

        return true;
    }

    /**
     * Validates that the argument is traversable.
     *
     * @param array|\Traversable $elements
     *
     * @return bool
     *
     * @throws \InvalidArgumentException.
     */
    protected function validateTraversable($elements)
    {
        if (!is_array($elements) && !$elements instanceof \Traversable) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expected an array or traversable as argument. Got: %s',
                    is_object($elements) ? get_class($elements) : gettype($elements)
                )
            );
        }

        return true;
    }
}
