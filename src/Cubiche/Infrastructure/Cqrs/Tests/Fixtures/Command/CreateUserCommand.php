<?php

/**
 * This file is part of the Cubiche/Cqrs component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Cqrs\Tests\Fixtures\Command;

use Cubiche\Core\Cqrs\Command\CommandInterface;
use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * CreateUserCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CreateUserCommand implements CommandInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $email;

    /**
     * CreateUserCommand constructor.
     *
     * @param string $username
     * @param string $password
     * @param string $email
     */
    public function __construct($username, $password, $email)
    {
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setEmail($email);
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    protected function setUsername($username)
    {
        $this->username = $username;
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
    protected function setPassword($password)
    {
        $this->password = $password;
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
    protected function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('username', Assert::stringType()->notBlank());
        $classMetadata->addPropertyConstraint('password', Assert::stringType()->notBlank());
        $classMetadata->addPropertyConstraint('email', Assert::email()->notBlank());
    }
}
