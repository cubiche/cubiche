<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Equatable\Tests\Fixtures;

use Cubiche\Core\Equatable\EquatableInterface;
use Cubiche\Core\Hashable\HashCoder;

/**
 * Value class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Value implements EquatableInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Value constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return $other instanceof self && $this->value() == $other->value();
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return HashCoder::defaultHashCode($this->value());
    }
}
