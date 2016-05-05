<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Tests\Fixtures;

use Cubiche\Domain\Model\IdInterface;

/**
 * Id class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Id implements IdInterface
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
     * @param mixed $other
     *
     * @return bool
     */
    public function equals($other)
    {
        return $this->toNative() == $other->toNative();
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
     * @return mixed
     */
    public function toNative()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return \strval($this->toNative());
    }
}
