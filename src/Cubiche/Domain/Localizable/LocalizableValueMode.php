<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Localizable;

use Cubiche\Domain\System\Enum;

/**
 * LocalizableValueMode.
 *
 * @method LocalizableValueMode STRICT()
 * @method LocalizableValueMode ANY()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class LocalizableValueMode extends Enum
{
    const STRICT = 'strict';
    const ANY = 'any';
}
