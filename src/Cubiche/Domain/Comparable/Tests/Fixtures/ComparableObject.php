<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Comparable\Tests\Fixtures;

use Cubiche\Domain\Comparable\ComparableInterface;

/**
 * ComparableObject class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ComparableObject implements ComparableInterface
{
    /**
     * @var int
     */
    protected $value;

    /**
     * ComparableObject constructor.
     *
     * @param int $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
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

        return $this->value == $other->value() ? 0 : ($this->value > $other->value() ? 1 : -1);
    }
}
