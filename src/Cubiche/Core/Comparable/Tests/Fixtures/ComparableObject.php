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
 * Comparable Object class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ComparableObject implements ComparableInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Comparable Object constructor.
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
    public function compareTo($other)
    {
        if (!$other instanceof self) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "%s".',
                $other,
                self::class
            ));
        }

        if ($this->value() instanceof ComparableInterface) {
            return $this->value()->compareTo($other->value());
        }

        return $this->value() == $other->value() ? 0 : ($this->value() > $other->value() ? 1 : -1);
    }
}
