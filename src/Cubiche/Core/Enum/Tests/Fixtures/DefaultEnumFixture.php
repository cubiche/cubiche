<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enum\Tests\Fixtures;

use Cubiche\Core\Enum\Enum;

/**
 * Test Enum.
 *
 * @method static DefaultEnumFixture FOO()
 * @method static DefaultEnumFixture BAR()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class DefaultEnumFixture extends Enum
{
    const __DEFAULT = self::BAR;

    const FOO = 'foo';
    const BAR = 'bar';
}
