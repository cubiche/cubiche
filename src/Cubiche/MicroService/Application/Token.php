<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application;

use Cubiche\Domain\System\DateTime\DateTime;

/**
 * Token class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Token
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
     * @var int
     */
    protected $issuedAt;

    /**
     * @var int
     */
    protected $notBefore;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var array
     */
    protected $permissions;

    /**
     * Token constructor.
     *
     * @param string $issuer
     * @param string $audience
     * @param string $userId
     * @param string $email
     * @param array  $permissions
     */
    public function __construct(
        $issuer,
        $audience,
        $userId,
        $email,
        array $permissions
    ) {
        $this->issuer = $issuer;
        $this->audience = $audience;
        $this->userId = $userId;
        $this->email = $email;
        $this->permissions = $permissions;

        $this->issuedAt = DateTime::now()->timestamp();
        $this->notBefore = DateTime::now()->timestamp();
    }

    /**
     * @return string
     */
    public function issuer()
    {
        return $this->issuer;
    }

    /**
     * @return string
     */
    public function audience()
    {
        return $this->audience;
    }

    /**
     * @return int
     */
    public function issuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * @return int
     */
    public function notBefore()
    {
        return $this->notBefore;
    }

    /**
     * @return string
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * @param array $values
     *
     * @return static
     */
    public static function fromArray(array $values)
    {
        $token = new static(
            $values['iss'],
            $values['aud'],
            $values['uid'],
            $values['email'],
            $values['perms']
        );

        $token->issuedAt = $values['iat'];
        $token->notBefore = $values['nbf'];

        return $token;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $this->issuedAt,
            'nbf' => $this->notBefore,
            'uid' => $this->userId,
            'email' => $this->email,
            'perms' => $this->permissions,
        );
    }
}
