<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Fixtures;

/**
 * FooObject class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FooObject
{
    /**
     * @var int
     */
    protected $bar;

    /**
     * FooObject constructor.
     *
     * @param int $bar
     */
    public function __construct($bar)
    {
        $this->bar = $bar;
    }

    /**
     * @return int
     */
    public function bar()
    {
        return $this->bar;
    }
}
