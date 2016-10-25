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
 * LogoutUserCommandHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LogoutUserCommandHandler
{
    /**
     * @param LogoutUserCommand $command
     *
     * @return bool
     */
    public function handle(LogoutUserCommand $command)
    {
        return $command->isLogin();
    }
}
