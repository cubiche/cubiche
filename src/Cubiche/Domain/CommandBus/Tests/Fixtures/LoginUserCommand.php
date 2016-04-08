<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\CommandBus\Tests\Fixtures;

/**
 * LoginUserCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LoginUserCommand
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var bool
     */
    protected $login = false;

    /**
     * LoginUserCommand constructor.
     *
     * @param $email
     * @param $password
     */
    public function __construct($email, $password)
    {
        $this->setEmail($email);
        $this->setPassword($password);
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isLogin()
    {
        return $this->login;
    }

    /**
     * @param bool $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }
}
