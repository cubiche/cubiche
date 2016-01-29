<?php

/**
 *  * This file is part of the Cubiche package.
 *  *
 *  * Copyright (c) Cubiche
 *  *
 *  * For the full copyright and license information, please view the LICENSE
 *  * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Identity;

use Cubiche\Domain\System\StringLiteral;

/**
 * Sequence Id Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SequenceId extends Id
{
    /**
     * @param string $value
     *
     * @return SequenceId
     */
    public static function fromNative($value)
    {
        return new self(StringLiteral::fromNative($value));
    }
}
