<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Tests\Fixtures;

use Cubiche\Domain\Command\CommandNameInterface;

/**
 * LogoutUserCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LogoutUserCommand implements CommandNameInterface
{
    /**
     * @var string
     */
    protected $email;

    /**
     * LogoutUserCommand constructor.
     *
     * @param $email
     */
    public function __construct($email)
    {
        $this->setEmail($email);
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
     * @return bool
     */
    public function isLogin()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function commandName()
    {
        return 'logout_user';
    }
}
