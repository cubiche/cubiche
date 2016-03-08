<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Asserters;

use Cubiche\Domain\Tests\Units\Asserter\ObjectAsserterInterface;

/**
 * Collection Asserter Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface CollectionAsserterInterface extends ObjectAsserterInterface
{
    /**
     * @return \Cubiche\Domain\Tests\Units\Asserter\ObjectAsserterInterface
     */
    public function size();

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isNotEmpty($failMessage = null);
}
