<?php

/**
 * This file is part of the cubiche/cubiche project.
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
