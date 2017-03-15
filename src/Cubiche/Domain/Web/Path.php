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
 * Path class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Path extends StringLiteral
{
    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($value)
    {
        $filteredValue = parse_url($value, PHP_URL_PATH);
        if ($filteredValue === null || strlen($filteredValue) != strlen($value)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "url path".',
                $value
            ));
        }

        parent::__construct($filteredValue);
    }
}
