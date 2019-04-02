<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Infrastructure\Services;

use Cubiche\MicroService\Application\Services\TokenContextInterface;
use Cubiche\MicroService\Application\Services\TokenDecoderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * TokenContext class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class TokenContext implements TokenContextInterface
{
    /**
     * The header name.
     *
     * @var string
     */
    protected $header = 'authorization';

    /**
     * The header prefix.
     *
     * @var string
     */
    protected $prefix = 'bearer';

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
            $this->tokenDecoder->decode($this->getJWT());

            return true;
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

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        if ($request->headers->has($this->header)) {
            $header = $request->headers->get($this->header);
        } else {
            $header = $this->fromAltHeaders($request);
        }

        if ($header && preg_match('/'.$this->prefix.'\s*(\S+)\b/i', $header, $matches)) {
            $this->setJWT($matches[1]);
        }
    }

    /**
     * Attempt to parse the token from some other possible headers.
     *
     * @param Request $request
     *
     * @return null|string
     */
    private function fromAltHeaders(Request $request)
    {
        if ($request->server->has('HTTP_AUTHORIZATION')) {
            return $request->server->get('HTTP_AUTHORIZATION');
        }

        return $request->server->get('REDIRECT_HTTP_AUTHORIZATION');
    }
}
