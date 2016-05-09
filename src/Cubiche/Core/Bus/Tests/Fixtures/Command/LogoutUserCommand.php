<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Fixtures\Command;

use Cubiche\Core\Bus\Command\CommandNamedInterface;

/**
 * LogoutUserCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LogoutUserCommand implements CommandNamedInterface
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
    public function name()
    {
        return 'logout_user';
    }
}
