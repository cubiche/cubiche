<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Identity;

use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\System\Enum;

/**
 * Abstract Enum Id Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class EnumId extends Enum implements IdInterface
{
}
