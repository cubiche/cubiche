<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Fixtures;

use Cubiche\Domain\Model\IdInterface;

/**
 * PostId class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostId implements IdInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * PostId constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return $this->toNative() == $other->toNative();
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public static function fromNative($value)
    {
        return new static($value);
    }

    /**
     * {@inheritdoc}
     */
    public function toNative()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return \strval($this->toNative());
    }
}
