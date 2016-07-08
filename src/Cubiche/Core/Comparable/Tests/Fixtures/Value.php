<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable\Tests\Fixtures;

use Cubiche\Core\Comparable\ComparableInterface;

/**
 * Comparable Value class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Value implements ComparableInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
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
}
