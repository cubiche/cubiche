<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\System\Enum;

/**
 * Test Enum.
 *
 * @method TestEnum FOO()
 * @method TestEnum BAR()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class TestEnum extends Enum
{
    const FOO = 'foo';
    const BAR = 'bar';
}
