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
     * @return bool
     */
    public static function isValid($host)
    {
        return preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $host) //valid chars check
            && preg_match('/^.{1,253}$/', $host) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $host) //length of each label
        ;
    }

    /**
     * @param string $host
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($host)
    {
        if (!self::isValid($host)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "host name".',
                $host
            ));
        }

        parent::__construct($host);
    }
}
