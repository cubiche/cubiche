<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Units\Traits;

/**
 * Test Case Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait TestCaseTrait
{
    /**
     * @return $this
     */
    public function then()
    {
        return static::__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return $this
     */
    public function given()
    {
        return static::__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return $this
     */
    public function define()
    {
        return static::__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return $this
     */
    public function let()
    {
        return static::__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return \Cubiche\Domain\Tests\Units\Asserter\VariableInterface
     */
    public function variable()
    {
        return static::__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return \Cubiche\Domain\Tests\Units\Asserter\ObjectInterface
     */
    public function object()
    {
        return static::__call(__FUNCTION__, func_get_args());
    }
}
