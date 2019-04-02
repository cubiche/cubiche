<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Services;

use Cubiche\MicroService\Application\Token;

/**
 * TokenContextInterface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface TokenContextInterface
{
    /**
     * Check that there is a token in the context.
     *
     * @return bool
     */
    public function hasToken();

    /**
     * Get the token from the context.
     *
     * @return Token
     */
    public function getToken();

    /**
     * Get the JWT from the context.
     *
     * @return string
     */
    public function getJWT();

    /**
     * Set the JWT to the context.
     *
     * @param string $jwt
     */
    public function setJWT($jwt);
}
