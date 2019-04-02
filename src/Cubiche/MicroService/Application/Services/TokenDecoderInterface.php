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
 * TokenDecoder interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface TokenDecoderInterface
{
    /**
     * @param string $jwt
     *
     * @return Token
     */
    public function decode($jwt);
}
