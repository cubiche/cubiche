<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\System\DateTime;

use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;
use Cubiche\Domain\Model\ValueObjectInterface;

/**
 * DateRange.
 *
 * @author Ivannis Suárez Jérez <ivannis.suarez@gmail.com>
 */
class DateRange implements ValueObjectInterface
{
    /**
     * @var Date
     */
    protected $from;

    /**
     * @var Date
     */
    protected $to;

    /**
     * @param Date $from
     * @param Date $to
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Date $from = null, Date $to = null)
    {
        if ($from !== null && $to !== null) {
            Assert::lessOrEqualThan(
                $from->toNative(),
                $to->toNative(),
                sprintf(
                    'Provided start date "%s" is not less or equal than end date "%s".',
                    $from->__toString(),
                    $to->__toString()
                )
            );
        }

        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return Date
     */
    public function from()
    {
        return $this->from;
    }

    /**
     * @return Date
     */
    public function to()
    {
        return $this->to;
    }

    /**
     * @param Date $date
     *
     * @return bool
     */
    public function contains(Date $date)
    {
        return ($this->from() === null || $this->from()->toNative() <= $date->toNative()) &&
            ($this->to() === null || $this->to()->toNative() >= $date->toNative())
        ;
    }

    /**
     * @param mixed $range
     *
     * @return bool
     */
    public function equals($range)
    {
        if ($this->from !== null && $this->to !== null && $range->from() !== null && $range->to() !== null) {
            return get_class($this) === get_class($range) &&
                $this->from()->equals($range->from()) &&
                $this->to()->equals($range->to())
            ;
        }

        if ($this->from !== null && $this->to === null && $range->from() !== null && $range->to() === null) {
            return get_class($this) === get_class($range) &&
                $this->from()->equals($range->from())
            ;
        }

        if ($this->from === null && $this->to !== null && $range->from() === null && $range->to() !== null) {
            return get_class($this) === get_class($range) &&
                $this->to()->equals($range->to())
            ;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        if ($this->from !== null && $this->to !== null) {
            return \sprintf('%s - %s', $this->from()->__toString(), $this->to()->__toString());
        }

        if ($this->from !== null) {
            return \sprintf('%s - [inf', $this->from()->__toString());
        }

        return \sprintf('inf] - %s', $this->to()->__toString());
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return $this->__toString();
    }
}
