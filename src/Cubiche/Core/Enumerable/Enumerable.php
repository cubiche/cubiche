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
 * Enumerable Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Enumerable extends AbstractEnumerable
{
    /**
     * @var \Iterator
     */
    private $iterator;

    /**
     * @param array|\Traversable $enumerable
     *
     * @return EnumerableInterface
     */
    public static function from($enumerable)
    {
        if ($enumerable instanceof EnumerableInterface) {
            return $enumerable;
        }

        $iterator = null;
        if (\is_array($enumerable)) {
            $iterator = new \ArrayIterator($enumerable);
        } elseif ($enumerable instanceof \Iterator) {
            $iterator = $enumerable;
        } elseif ($enumerable instanceof \Traversable) {
            $iterator = new \IteratorIterator($enumerable);
        }

        if ($iterator !== null) {
            return new self($iterator);
        }

        if ($enumerable instanceof \Closure) {
            return self::from($enumerable());
        }

        throw new \InvalidArgumentException('The enumerable must be array or \\Traversable.');
    }

    /**
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public static function emptyEnumerable()
    {
        return self::from(new \EmptyIterator());
    }

    /**
     * @param \Iterator $iterator
     */
    public function __construct(\Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}
