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
use Cubiche\MicroService\Application\Exception\InvalidTokenException;
use Firebase\JWT\JWT;

/**
 * TokenManager class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class TokenManager implements TokenEncoderInterface, TokenDecoderInterface
{
    /**
     * @var string
     */
    protected $issuer;

    /**
     * @var string
     */
    protected $audience;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var string
     */
    protected $algorithm;

    /**
     * TokenManager constructor.
     *
     * @param string $issuer
     * @param string $audience
     * @param string $publicKey
     * @param string $privateKey
     * @param string $algorithm
     */
    public function __construct($issuer, $audience, $publicKey, $privateKey, $algorithm = 'RS256')
    {
        $this->issuer = $issuer;
        $this->audience = $audience;
        $this->publicKey = file_get_contents($publicKey);
        $this->privateKey = file_get_contents($privateKey);
        $this->algorithm = $algorithm;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($userId, $email, array $permissions)
    {
        $token = new Token(
            $this->issuer,
            $this->audience,
            $userId,
            $email,
            $permissions
        );

        return JWT::encode($token->toArray(), $this->privateKey, $this->algorithm);
    }

    /**
     * {@inheritdoc}
     */
    public function decode($jwt)
    {
        try {
            return Token::fromArray((array) JWT::decode($jwt, $this->publicKey, array($this->algorithm)));
        } catch (\Exception $e) {
            throw new InvalidTokenException($e->getMessage());
        }
    }
}
