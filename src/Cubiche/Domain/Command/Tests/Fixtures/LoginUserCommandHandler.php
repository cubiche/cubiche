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

/**
 * LoginUserCommandHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LoginUserCommandHandler
{
    /**
     * @param LoginUserCommand $command
     *
     * @return bool
     */
    public function handle(LoginUserCommand $command)
    {
        return true;
    }
}
