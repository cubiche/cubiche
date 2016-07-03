<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Visitor\Tests\Fixtures;

/**
 * Value Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Value extends Expression
{
    /**
     * @var int|float
     */
    protected $value;

    /**
     * @param int|float $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return int|float
     */
    public function value()
    {
        return $this->value;
    }
}
