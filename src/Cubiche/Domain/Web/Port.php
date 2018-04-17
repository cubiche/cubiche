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
use Cubiche\Domain\System\Integer;

/**
 * Port class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Port extends Integer
{
    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        Assert::port($value);

        parent::__construct($value);
    }
}
