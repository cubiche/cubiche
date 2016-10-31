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

/**
 * LoginUserMessageListener class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LoginUserMessageListener
{
    /**
     * @param LoginUserMessage $event
     *
     * @return bool
     */
    public function loginUser(LoginUserMessage $event)
    {
        $event->setEmail('info@cubiche.org');
    }
}
