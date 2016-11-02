<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Fixtures\Message;

use Cubiche\Core\Bus\MessageNamedInterface;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * LogoutUserMessage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LogoutUserMessage implements MessageNamedInterface
{
    /**
     * @var string
     */
    protected $email;

    /**
     * LogoutUserMessage constructor.
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
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function named()
    {
        return 'logout_user';
    }
}
