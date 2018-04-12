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
 * EmailAddress class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EmailAddress extends StringLiteral
{
    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        Assert::email($value, sprintf('Argument "%s" is invalid. Allowed types for argument are "email".', $value));

        parent::__construct($value);
    }

    /**
     * @return string
     */
    public function user()
    {
        $parts = explode('@', $this->toNative());

        return new StringLiteral($parts[0]);
    }

    /**
     * @return string
     */
    public function domain()
    {
        $parts = \explode('@', $this->toNative());
        $domain = \trim($parts[1], '[]');

        return new StringLiteral($domain);
    }
}
