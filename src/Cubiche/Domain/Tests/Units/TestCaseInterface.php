<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Units;

/**
 * Test Case Interface.
 *
 * @method \Cubiche\Domain\Tests\Units\TestCaseInterface|$this if()
 * @method \Cubiche\Domain\Tests\Units\TestCaseInterface|$this and()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface TestCaseInterface
{
    /**
     * @return $this
     */
    public function then();

    /**
     * @return $this
     */
    public function given();

    /**
     * @return $this
     */
    public function define();

    /**
     * @return $this
     */
    public function let();

    /**
     * @return \Cubiche\Domain\Tests\Units\Asserter\VariableAsserterInterface
     */
    public function variable();

    /**
     * @return \Cubiche\Domain\Tests\Units\Asserter\ObjectAsserterInterface
     */
    public function object();
}
