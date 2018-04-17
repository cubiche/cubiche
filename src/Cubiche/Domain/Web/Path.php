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
use Cubiche\Domain\System\StringLiteral;

/**
 * Path class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Path extends StringLiteral
{
    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        Assert::path($value);

        parent::__construct($value);
    }
}
