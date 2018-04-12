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

use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;

/**
 * IPAddress class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class IPAddress extends Host
{
    /**
     * @param string $ip
     *
     * @throws InvalidArgumentException
     */
    public function __construct($ip)
    {
        Assertion::oneOf(Assertion::ip(), Assertion::ipv4(), Assertion::ipv6())->assert($ip, sprintf(
            'Argument "%s" is invalid. Allowed types for argument are "ip address".',
            $ip
        ));

        parent::__construct((string) $ip);
    }
}
