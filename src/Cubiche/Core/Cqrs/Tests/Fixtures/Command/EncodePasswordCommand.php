<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Cqrs\Tests\Fixtures\Command;

use Cubiche\Core\Cqrs\Command\CommandInterface;

/**
 * EncodePasswordCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EncodePasswordCommand implements CommandInterface
{
    /**
     * @var string
     */
    protected $password;

    /**
     * EncodePasswordCommand constructor.
     *
     * @param $password
     */
    public function __construct($password)
    {
        $this->setPassword($password);
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
}
