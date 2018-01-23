<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Identity\Validation\Rules;

use Cubiche\Domain\Identity\UUID as Identifier;

/**
 * Uuid class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Uuid extends AbstractRule
{
    /**
     * @param $input
     *
     * @return bool
     */
    public function validate($input)
    {
        return Identifier::isValidUUID($input);
    }
}
