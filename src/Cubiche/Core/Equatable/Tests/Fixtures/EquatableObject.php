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

/**
 * EquatableObject class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EquatableObject implements EquatableInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * EquatableObject constructor.
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
     * @param EquatableObject $other
     *
     * @return bool
     */
    public function equals($other)
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->value() == $other->value();
    }
}
