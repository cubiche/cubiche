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
     * @param array|\Traversable $enumerable
     * @param int                $offset
     * @param int                $length
     */
    public function __construct($enumerable, $offset, $length = null)
    {
        parent::__construct($enumerable);

        if (!\is_integer($offset) || $offset < 0) {
            throw new \InvalidArgumentException('The $offset parameter must be non-negative integer.');
        }

        if ($length !== null && !\is_integer($length)) {
            throw new \InvalidArgumentException('The $length parameter must be non-negative integer.');
        }

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

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        return new static($this->enumerable(), $this->offset() + $offset, $this->calculateLimit($offset, $length));
    }

    /**
     * @param int $offset
     * @param int $length
     *
     * @return int
     */
    protected function calculateLimit($offset, $length = null)
    {
        if ($this->length() !== null && $offset >= $this->length()) {
            return 0;
        }
        $limit = $length;
        if ($this->length() !== null) {
            $limit = $this->length() - (int) $offset;
            if ($length !== null) {
                $limit = \min([$limit, $length]);
            }
        }

        return $limit;
    }
}
