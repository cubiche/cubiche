<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\System;

/**
 * Rounding Mode Enum.
 *
 * @method RoundingMode HALF_UP()
 * @method RoundingMode HALF_DOWN()
 * @method RoundingMode HALF_EVEN()
 * @method RoundingMode HALF_ODD()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class RoundingMode extends Enum
{
    const HALF_UP = PHP_ROUND_HALF_UP;
    const HALF_DOWN = PHP_ROUND_HALF_DOWN;
    const HALF_EVEN = PHP_ROUND_HALF_EVEN;
    const HALF_ODD = PHP_ROUND_HALF_ODD;
}
