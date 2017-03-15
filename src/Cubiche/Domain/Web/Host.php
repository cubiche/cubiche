<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Web;

use Cubiche\Domain\System\StringLiteral;

/**
 * Host class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class Host extends StringLiteral
{
    /**
     * {@inheritdoc}
     */
    public static function fromNative($host)
    {
        if (filter_var($host, FILTER_VALIDATE_IP) !== false) {
            return new IPAddress($host);
        }

        return new HostName($host);
    }
}
