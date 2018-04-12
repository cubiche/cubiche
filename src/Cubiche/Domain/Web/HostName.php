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

use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;

/**
 * HostName class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HostName extends Host
{
    /**
     * @param string $host
     *
     * @throws InvalidArgumentException
     */
    public function __construct($host)
    {
        Assert::hostName($host);

        parent::__construct($host);
    }
}
