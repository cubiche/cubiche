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
 * Sliced Enumerable Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SlicedEnumerable extends EnumerableDecorator
{
    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $length;

    /**
     * @param EnumerableInterface $enumerable
     * @param int                 $offset
     * @param int                 $length
     */
    public function __construct(EnumerableInterface $enumerable, $offset, $length = null)
    {
        parent::__construct($enumerable);

        $this->offset = $offset;
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $length = $this->length();
        if ($length === 0) {
            return new \EmptyIterator();
        }

        return new \LimitIterator(parent::getIterator(), $this->offset(), $length === null ? -1 : $length);
    }
}
