<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable\Tests\Fixtures;

use Cubiche\Core\Equatable\EquatableInterface;
use Cubiche\Core\Comparable\ComparableInterface;
use Cubiche\Core\Hashable\HashCoder;

/**
 * Value class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Value implements EquatableInterface, ComparableInterface
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

    /**
     * {@inheritdoc}
     */
    public function compareTo($other)
    {
        if ($other instanceof self) {
            return $this->value() - $other->value();
        }

        if (\is_numeric($other)) {
            return $this->compareTo(new self($other));
        }

        throw new \InvalidArgumentException(sprintf(
            'Argument "%s" is invalid. Allowed types for argument are "%s" or numeric values.',
            $other,
            self::class
        ));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '('.(string) $this->value().')';
    }
}
