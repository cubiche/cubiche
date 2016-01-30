<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Identity;

use Cubiche\Domain\Model\NativeValueObject;
use Cubiche\Domain\Model\IdInterface;

/**
 * Abstract Id Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class Id extends NativeValueObject implements IdInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     */
    protected function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Model\NativeValueObjectInterface::toNative()
     */
    public function toNative()
    {
        return $this->value->toNative();
    }
}
