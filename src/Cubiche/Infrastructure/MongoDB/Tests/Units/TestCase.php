<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Tests\Units;

use Cubiche\Tests\TestCase as BaseTestCase;

/**
 * Abstract Test Case Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    use DocumentManagerTestCaseTrait;
}
