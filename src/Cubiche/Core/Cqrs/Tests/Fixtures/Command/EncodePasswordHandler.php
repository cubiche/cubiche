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

/**
 * EncodePasswordHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EncodePasswordHandler
{
    /**
     * @var string
     */
    protected $algorithm;

    /**
     * EncodePasswordHandler constructor.
     *
     * @param string $algorithm
     */
    public function __construct($algorithm = 'md5')
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @param EncodePasswordCommand $command
     */
    public function encodePassword(EncodePasswordCommand $command)
    {
        $command->setPassword(call_user_func($this->algorithm, $command->password()));
    }
}
