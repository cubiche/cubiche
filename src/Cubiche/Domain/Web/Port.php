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
     * @throws \InvalidArgumentException
     */
    public function __construct($value)
    {
        $options = array(
            'options' => array(
                'min_range' => 0,
                'max_range' => 65535,
            ),
        );

        $value = filter_var($value, FILTER_VALIDATE_INT, $options);
        if ($value === false) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "int".',
                $value
            ));
        }

        parent::__construct($value);
    }
}
