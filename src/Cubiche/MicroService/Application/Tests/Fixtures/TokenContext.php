<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Tests\Fixtures;

use Cubiche\MicroService\Application\Services\TokenContextInterface;
use Cubiche\MicroService\Application\Services\TokenDecoderInterface;

/**
 * TokenContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class TokenContext implements TokenContextInterface
{
    /**
     * @var string
     */
    protected $jwt;

    /**
     * @var TokenDecoderInterface
     */
    protected $tokenDecoder;

    /**
     * TokenContext constructor.
     *
     * @param TokenDecoderInterface $tokenDecoder
     */
    public function __construct(TokenDecoderInterface $tokenDecoder)
    {
        $this->tokenDecoder = $tokenDecoder;
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken()
    {
        if ($this->getJWT() !== null) {
            try {
                $this->tokenDecoder->decode($this->getJWT());

                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->tokenDecoder->decode($this->getJWT());
    }

    /**
     * {@inheritdoc}
     */
    public function getJWT()
    {
        return $this->jwt;
    }

    /**
     * {@inheritdoc}
     */
    public function setJWT($jwt)
    {
        $this->jwt = $jwt;
    }
}
